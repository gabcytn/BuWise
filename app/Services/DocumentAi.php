<?php

namespace App\Services;

use Google\Cloud\DocumentAI\V1\Client\DocumentProcessorServiceClient;
use Google\Cloud\DocumentAI\V1\ProcessRequest;
use Google\Cloud\DocumentAI\V1\RawDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentAi
{
    public function __construct(
        public string $filename,
        public string $mimeType
    ) {}

    public function parse(): string
    {
        try {
            $projectId = env('GC_PROJECT_ID');
            $location = env('GC_LOCATION');
            $processorId = env('GC_PROCESSOR_ID');

            // Path for the file to read.
            $documentPath = Storage::disk('public')->path('invoices/' . $this->filename);

            // Create Client.
            $client = new DocumentProcessorServiceClient();

            // Read in file.
            $handle = fopen($documentPath, 'rb');
            $contents = fread($handle, filesize($documentPath));
            fclose($handle);

            // Load file contents into a RawDocument.
            $rawDocument = (new RawDocument())
                ->setContent($contents)
                ->setMimeType($this->mimeType);

            // Get the Fully-qualified Processor Name.
            $fullProcessorName = $client->processorName($projectId, $location, $processorId);

            // Send a ProcessRequest and get a ProcessResponse.
            $request = (new ProcessRequest())
                ->setName($fullProcessorName)
                ->setRawDocument($rawDocument);

            $response = $client->processDocument($request);

            // Delete temporary image stored locally.
            Storage::disk('public')->delete('invoices/' . $this->filename);
            return $response->getDocument()->getText();
        } catch (\Exception $e) {
            Log::error('Error calling document AI api: ' . $e->getMessage());
            return '';
        }
    }
}

?>
