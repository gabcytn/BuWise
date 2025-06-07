<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class InsightsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $accId = getAccountantId($user);
        $clients = Cache::remember("$accId-clients", 3600, function () use ($user) {
            return getClients($user);
        });
        $periods = ['This Year', 'This Month', 'This Week', 'Last Week', 'Last Month', 'Last Year', 'All Time'];
        return view('reports.insights', [
            'has_data' => false,
            'periods' => $periods,
            'clients' => $clients,
        ]);
    }
}
