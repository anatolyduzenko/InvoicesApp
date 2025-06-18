<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Repositories\InvoiceRepository;
use Inertia\Inertia;

class InvoiceCloneController extends Controller
{
    public function __construct(protected InvoiceRepository $invoiceRepository) {}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Invoice $invoice)
    {
        return Inertia::render('invoices/Edit', [
            'accounts' => Account::all(),
            'company' => Company::first(),
            'customers' => Customer::all(),
            'invoice' => $this->invoiceRepository->clone($invoice),
        ]);
    }
}
