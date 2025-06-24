<?php

namespace App\Http\Controllers;

use App\Models\FailedInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FailedInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $failed_invoices = $user->failedInvoices();
        $accountant_id = getAccountantId($user);
        $clients = Cache::remember("$accountant_id-clients", 3600, function () use ($user) {
            return getClients($user);
        });

        $filters = [
            'client' => $request->client,
            'order_by' => $request->order_by
        ];

        if (!$filters['client'])
            $failed_invoices = $failed_invoices->where('client_id', '=', $filters['client']);
        if (!$filters['order_by']) {
            switch ($filters['order_by']) {
                case 'date':
                    $failed_invoices = $failed_invoices->orderBy('created_at');
                    break;
                case 'client':
                    $failed_invoices = $failed_invoices->orderBy('client_id');
                    break;
                default:
                    break;
            }
        }

        $failed_invoices = $failed_invoices->paginate(12)->appends($filters);

        return view('invoices.failed', [
            'invoices' => $failed_invoices,
            'clients' => $clients,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FailedInvoice $failed)
    {
        Storage::disk('public')->delete('temp/' . $failed->filename);
        FailedInvoice::destroy($failed->id);
        return Response::json([
            'message' => 'Successfully deleted invoice',
        ]);
    }
}
