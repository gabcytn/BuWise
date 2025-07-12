<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class BinController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'filter-by' => 'nullable|in:journal,invoice,all',
            'sort-by' => 'nullable|in:deleted_at,reference_no',
            'search' => 'nullable|string',
        ]);
        $transactions = Transaction::onlyTrashed();

        $filter_by = $request->query('filter-by');
        $order_by = $request->query('sort-by');
        $search = $request->query('search');
        if ($filter_by && $filter_by !== 'all')
            $transactions = $transactions->where('type', '=', $filter_by);
        if ($order_by)
            $transactions = $transactions->orderBy($order_by);
        else
            $transactions = $transactions->orderBy('deleted_at');
        if ($search && is_numeric($search))
            $transactions = $transactions->where('reference_no', '=', $search);

        $transactions = $transactions->get();

        return view('bin.index', [
            'transactions' => $transactions,
        ]);
    }

    public function restore(Request $request)
    {
        return $this->restoreOrDelete($request, 'restore');
    }

    public function delete(Request $request)
    {
        return $this->restoreOrDelete($request, 'delete');
    }

    private function restoreOrDelete(Request $request, string $action)
    {
        $request->validate([
            'items' => 'required|array',
        ]);

        try {
            DB::beginTransaction();
            foreach ($request->items as $id) {
                $tr = Transaction::withTrashed()->find($id);
                if ($tr->client->accountant_id !== $request->user()->id)
                    throw new \Exception('User does not own this item');

                switch ($action) {
                    case 'restore':
                        $tr->restore();
                        break;
                    case 'delete':
                        if ($tr->image)
                            Storage::delete('invoices/' . $tr->image);
                        $tr->forceDelete();
                        break;
                    default:
                        throw new InvalidArgumentException('Action must be either restore or delete.');
                        break;
                }
            }
            DB::commit();
            return Response::json([
                'message' => "Successfully {$action}d all items",
            ]);
        } catch (\Exception $e) {
            Log::warning("User tried to $action an item which they do not own.");
            Log::warning($e->getMessage());
            DB::rollBack();
            return Response::json([
                'message' => "An item to $action is not the requestee's item",
            ], 403);
        }
    }
}
