<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class MobileInvoiceController extends Controller
{
    /*
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Log::info('Start time: ' . now());
        $request->validate([
            'file' => ['required', File::image()->max(10000)]
        ]);
        try {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();

            // dummy values
            $invoice = Invoice::create([
                'client_id' => $request->user()->id,
                'image' => $filename,
            ]);

            Storage::disk('public')->put('invoices/' . $filename, file_get_contents($file));
            Log::info('Successfully saved an invoice');
            Log::info('End time: ' . now());
            return Response::json([
                'message' => 'Successfully created invoice'
            ], 201);
        } catch (\Exception $e) {
            return Response::json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
