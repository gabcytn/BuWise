<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class JournalIndex
{
    private const ITEMS_PER_PAGE = 6;

    public function index(Request $request)
    {
        $user = $request->user();
        $filter = $request->only(['client', 'period', 'search', 'sort']);
        $clients = $this->getCachedClients($user);

        if (empty($filter)) {
            $filter = [
                'client' => 'all',
                'period' => 'all_time',
                'search' => '',
            ];
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

        $query = $query
            ->groupBy('je.id', 'client.name', 'tt.name', 'status.description', 'je.description', 'je.date', 'creator.name');

        $query = $this->getOrderBy($query, $filter);

        return $query
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
        $client = $filter['client'] ?? 'all';
        $period = $filter['period'] ?? 'all_time';
        $search = $filter['search'] ?? null;

        // Filter by client if not "all"
        if ($client !== 'all') {
            $query->where('je.client_id', '=', $client);
        }

        switch ($period) {
            case 'all_time':
                break;
            case 'this_year':
                $start = Carbon::now()->startOfYear()->toDateString();
                $end = Carbon::now()->endOfYear()->toDateString();
                $query->whereBetween('je.date', [$start, $end]);
                break;
            case 'last_year':
                $start = Carbon::now()->subYear()->startOfYear()->toDateString();
                $end = Carbon::now()->subYear()->endOfYear()->toDateString();
                $query->whereBetween('je.date', [$start, $end]);
                break;
            default:
                break;
        }

        if ($search) {
            $query->where('je.id', '=', $search);
        }

        return $query;
    }

    private function getOrderBy($query, array $filter)
    {
        $sort = $filter['sort'] ?? null;

        if (!$sort)
            return $query->orderByDesc('je.id');

        switch ($sort) {
            case 'journal_id':
                $query->orderBy('je.id');
                break;
            case 'client_name':
                $query->orderBy('client.name');
                break;
            case 'transaction_type':
                $query->orderBy('tt.name');
                break;
            case 'description':
                $query->orderBy('je.description');
                break;
            case 'amount':
                $query->orderBy('amount');
                break;
            case 'date':
                $query->orderBy('je.date');
                break;
            case 'created_by':
                $query->orderBy('creator');
                break;
            case 'status':
                $query->orderBy('status.description');
                break;
            default:
                $query->orderByDesc('je.id');
                break;
        }

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
