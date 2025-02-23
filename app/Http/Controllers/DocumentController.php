<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ResponseMiddleware;
use App\Http\Requests\DocumentRequest;
use App\Models\Transaction\Document;
use Dedoc\Scramble\Attributes\Group;
use Spatie\RouteDiscovery\Attributes\Route;

#[Route(middleware: [ResponseMiddleware::class])]
#[Group('document')]
class DocumentController extends Controller
{
    #[Route('POST')]
    public function store(DocumentRequest $request)
    {
        $document = $request->file('document');
        $documentFileName = $document->hashName();
        $documentSize = $document->getSize();
        $documentMimeType = $document->getMimeType();
        $documentLink = $document->storeAs('documents', $documentFileName);

        return Document::create($request->merge([
            'document_name' => "/storage/$documentFileName",
            'document_link' => $documentLink,
            'document_size' => $documentSize,
            'document_mime_type' => $documentMimeType
        ])->all());
    }
}
