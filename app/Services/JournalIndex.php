<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class JournalIndex
{
    private const ITEMS_PER_PAGE = 6;

    private string $title;
    private string $subtitle;
    private string $route;

    public function __construct(string $title, string $subtitle, string $route)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->route = $route;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $filter = $request->only(['client', 'period', 'search', 'sort', 'transaction_type']);
        $clients = $this->getCachedClients($user);

        if (!array_key_exists('period', $filter))
            $filter['period'] = $this->route === 'index' ? 'this_year' : 'archived';
        if (!array_key_exists('client', $filter))
            $filter['client'] = 'all';
        if (!array_key_exists('transaction_type', $filter))
            $filter['transaction_type'] = 'all';

        try {
            $entries = $this->getFilteredEntries($user, $filter);
            return view('journal-entries.index', [
                'clients' => $clients,
                'entries' => $entries,
                'title' => $this->title,
                'subtitle' => $this->subtitle,
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
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
            ->groupBy('je.id', 'client.name', 'je.kind', 'je.description', 'je.date', 'creator.name');

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
        $accId = getAccountantId($user);
        $query = DB::table('transactions as je')
            ->join('users as client', 'client.id', '=', 'je.client_id')
            ->join('users as creator', 'creator.id', '=', 'je.created_by')
            ->join('ledger_entries as le', 'le.transaction_id', '=', 'je.id')
            ->select(
                'je.id',
                'client.name as client_name',
                'creator.name as creator',
                'je.reference_no',
                'je.kind as transaction_type',
                'je.status AS status',
                'je.description',
                DB::raw('MAX(le.amount) as amount'),
                'je.date'
            )
            ->where('client.accountant_id', '=', $accId)
            ->where('je.type', '=', 'journal');
        return $this->route === 'archives'
            ? $query->where('je.status', '=', 'archived')
            : $query->where('je.status', '!=', 'archived');
    }

    /**
     * Apply filters to the query based on user input
     */
    private function applyFilters($query, array $filter)
    {
        $client = $filter['client'];
        $period = $filter['period'];
        $transaction_type = $filter['transaction_type'];
        $search = $filter['search'] ?? null;

        // Filter by client if not "all"
        if ($client !== 'all') {
            $query->where('je.client_id', '=', $client);
        }
        switch ($transaction_type) {
            case 'all':
                break;
            case 'sales':
                $query->where('je.kind', '=', 'sales');
                break;
            case 'purchases':
                $query->where('je.kind', '=', 'purchases');
                break;
            default:
                break;
        }

        switch ($period) {
            case 'this_year':
                $start = Carbon::now()->startOfYear()->toDateString();
                $end = Carbon::now()->endOfYear()->toDateString();
                break;
            case 'this_month':
                $start = Carbon::now()->startOfMonth()->toDateString();
                $end = Carbon::now()->endOfMonth()->toDateString();
                break;
            case 'this_week':
                $start = Carbon::now()->startOfWeek(Carbon::SUNDAY)->toDateString();
                $end = Carbon::now()->endOfWeek(Carbon::SATURDAY)->toDateString();
                break;
            case 'last_week':
                $start = Carbon::now()->subWeek()->startOfWeek(Carbon::SUNDAY)->toDateString();
                $end = Carbon::now()->subWeek()->endOfWeek(Carbon::SATURDAY)->toDateString();
                break;
            case 'last_month':
                $start = Carbon::now()->subMonthsNoOverflow()->startOfMonth()->toDateString();
                $end = Carbon::now()->subMonthsNoOverflow()->endOfMonth()->toDateString();
                break;
            case 'last_year':
                $start = Carbon::now()->subYear()->startOfYear()->toDateString();
                $end = Carbon::now()->subYear()->endOfYear()->toDateString();
                break;
            case 'archived':
                $start = '1970-01-01';
                $end = Carbon::now()->subYear()->endOfYear()->toDateString();
                break;
            default:
                $start = Carbon::now()->startOfYear()->toDateString();
                $end = Carbon::now()->endOfYear()->toDateString();
                break;
        }
        $query->whereBetween('je.date', [$start, $end]);
        if ($search)
            $query->where('je.reference_no', '=', $search);

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
                $query->orderBy('je.kind');
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
                $query->orderBy('je.status');
                break;
            default:
                $query->orderByDesc('je.id');
                break;
        }

        return $query;
    }
}
