<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Invoice;

class InvoiceViewController extends Controller
{
    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'account', 'company', 'customer']);

        return view('invoices.pdf', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        $tempPath = storage_path('app/public/' . Str ::uuid() . '.pdf');

        $html = view('invoices.pdf',  [
            'invoice' => $invoice->load(['items', 'account', 'company', 'customer'])
        ])->render();

        $response = Http::post('http://puppeteer:3000/generate-pdf', [
            'html' => $html
        ]);
        
        file_put_contents($tempPath, $response->body());

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }
}
