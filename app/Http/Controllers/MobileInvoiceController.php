<?php

namespace App\Http\Controllers;

use App\Events\InvoiceCreated;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
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
        Gate::authorize('create', Invoice::class);
        $request->validate([
            'file' => ['required', File::image()->max(10000)]
        ]);
        try {
            $file = $request->file('file');
            $filename = $this->storeImageToAws($file);

            $invoice = Invoice::create([
                'client_id' => $request->user()->id,
                'image' => $filename,
            ]);

            // TODO: send actual file image to Document AI for parsing,
            // then to openAI api for structured JSON,
            // then to robocorp for journal entrying.

            return Response::json([
                'message' => 'Successfully created invoice'
            ], 201);
        } catch (\Exception $e) {
            return Response::json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function storeImageToAws($file): string
    {
        $path = $file->store('invoices/', 's3');
        Log::info("Filename without basename: $path");
        return basename($path);
    }
}
