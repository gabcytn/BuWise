<?php

namespace App\Services;

use App\Models\JournalEntry;
use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class JournalIndex
{
    private const ITEMS_PER_PAGE = 6;

    public function index(Request $request)
    {
        $user = $request->user();
        $filter = $request->only(['type', 'invoice', 'client']);
        $clients = $this->getCachedClients($user);

        if (empty($filter)) {
            return view('journal-entries.index', [
                'clients' => $clients,
                'entries' => [],
            ]);
        }

        try {
            $entries = $this->getFilteredEntries($user, $filter);

            return view('journal-entries.index', [
                'clients' => $clients,
                'entries' => $entries
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Get cached client list for the user
     */
    private function getCachedClients(User $user)
    {
        return Cache::remember($user->id . '-clients', 3600, function () use ($user) {
            return getClients($user);
        });
    }

    /**
     * Apply filters to journal entries query
     */
    private function getFilteredEntries(User $user, array $filter)
    {
        $query = $this->buildBaseQuery($user);
        $query = $this->applyFilters($query, $filter);

        return $query
            ->groupBy('je.id', 'client.name', 'tt.name', 'status.description', 'je.description', 'je.date', 'creator.name')
            ->orderByDesc('je.id')
            ->paginate(self::ITEMS_PER_PAGE)
            ->appends($filter);
    }

    /**
     * Build the base query for journal entries
     */
    private function buildBaseQuery(User $user)
    {
        $accId = $user->role_id === Role::ACCOUNTANT ? $user->id : $user->accountant->id;
        return DB::table('journal_entries as je')
            ->join('users as client', 'client.id', '=', 'je.client_id')
            ->join('users as creator', 'creator.id', '=', 'je.created_by')
            ->join('transaction_types as tt', 'tt.id', '=', 'je.transaction_type_id')
            ->join('ledger_entries as le', 'le.journal_entry_id', '=', 'je.id')
            ->join('status', 'status.id', '=', 'je.status_id')
            ->select(
                'je.id',
                'client.name as client_name',
                'creator.name as creator',
                'tt.name as transaction_type',
                'status.description AS status',
                'je.description',
                DB::raw('MAX(le.amount) as amount'),
                'je.date'
            )
            ->where('client.accountant_id', '=', $accId);
    }

    /**
     * Apply filters to the query based on user input
     */
    private function applyFilters($query, array $filter)
    {
        $type = $filter['type'] ?? 'all';
        $client = $filter['client'] ?? 'all';
        $invoiceFilter = $filter['invoice'] ?? null;
        $hasInvoiceFilter = isset($filter['invoice']);

        // Filter by client if not "all"
        if ($client !== 'all') {
            $query->where('je.client_id', '=', $client);
        }

        // Type-specific filtering
        if ($type === 'journals') {
            $query->where('je.invoice_id', '=', null);
        } elseif ($type === 'invoices') {
            $query->where('je.invoice_id', '!=', null);

            // Apply invoice status filter if specified
            if ($invoiceFilter !== 'all' && $hasInvoiceFilter) {
                $query->where('je.status_id', '=', Status::LOOKUP[$invoiceFilter]);
            }
        }

        // Validate filter combinations
        $this->validateFilterCombination($type, $client, $invoiceFilter, $hasInvoiceFilter);

        return $query;
    }

    /**
     * Validate that the filter combination is allowed
     */
    private function validateFilterCombination($type, $client, $invoiceFilter, $hasInvoiceFilter)
    {
        // Invalid case: Invoice filter without invoice type
        if ($type !== 'invoices' && $hasInvoiceFilter) {
            throw new \Exception('Invalid combinations of filter');
        }

        return true;
    }
}

?>
