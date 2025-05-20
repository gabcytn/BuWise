<?php

namespace App\Http\Controllers;

use App\Events\InvoiceCreated;
use App\Models\Invoice;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        $request->validate([
            'file' => ['required', File::image()->max(10000)]
        ]);
        try {
            // Storage::disk('public')->put('invoices/' . $filename, file_get_contents($file));
            $filename = $this->storeImageToAws($request);
            $url = Storage::temporaryUrl('invoices/' . $filename, now()->addMinutes(10));

            $invoice = Invoice::create([
                'client_id' => $request->user()->id,
                'image' => $filename,
            ]);

            // NOTE: temporarily disable rpa bot trigger.
            // InvoiceCreated::dispatch($invoice->id, $url, $request->user());

            // NOTE: temporary solution while rpa not set:
            $oldLinks = Cache::get($request->user()->id . '-invoices');
            $oldLinks[] = Storage::temporaryUrl('invoices/' . $filename, now()->addMinutes(10080));
            Cache::put($request->user()->id . '-invoices', $oldLinks, $seconds = 604800);
            // NOTE: end

            return Response::json([
                'message' => 'Successfully created invoice'
            ], 201);
        } catch (\Exception $e) {
            return Response::json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function storeImageToAws(Request $request)
    {
        $path = $request->file('file')->store('invoices/', 's3');
        return basename($path);
    }
}
