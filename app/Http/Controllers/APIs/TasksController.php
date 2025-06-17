<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
{
    public function index(Request $request)
    {
        return DB::table('tasks')
            ->join('users', 'users.id', '=', 'tasks.created_by')
            ->where('client_id', '=', $request->user()->id)
            ->select(
                'tasks.id',
                'tasks.name',
                'description',
                'status',
                'priority',
                'start_date',
                'end_date',
                'users.name AS created_by',
            )
            ->get();
    }
}
