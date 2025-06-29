<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\Templates\TemplateManager;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class InvoiceViewController extends Controller
{
    /**
     * Preview invoice for printing.
     */
    public function show(Invoice $invoice, TemplateManager $templateManager)
    {
        $invoice->load(['items', 'account', 'company', 'customer']);

        $view = $templateManager->getViewByKey($invoice->customer->template_name ?? 'default');

        return view($view, compact('invoice'));
    }

    /**
     * Generates PDF file with invoice data.
     */
    public function download(Invoice $invoice, TemplateManager $templateManager)
    {
        $tempPath = storage_path('app/public/'.Str::uuid().'.pdf');

        $view = $templateManager->getViewByKey($invoice->customer->template_name ?? 'default');

        $html = view($view, [
            'invoice' => $invoice->load(['items', 'account', 'company', 'customer']),
        ])->render();

        $url = sprintf(
            'http://%s:%s/generate-pdf',
            config('puppeteer.host'),
            config('puppeteer.port')
        );

        $response = Http::timeout(60)->post($url, [
            'html' => $html,
        ]);

        file_put_contents($tempPath, $response->body());

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }
}
