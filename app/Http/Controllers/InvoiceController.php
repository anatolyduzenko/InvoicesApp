<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Models\Account;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Repositories\InvoiceRepository;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceRepository $invoiceRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('invoices/List', [
            'invoices' => $this->invoiceRepository->getAll(with: ['account', 'customer'], perPage: 10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = Company::first();

        return Inertia::render('invoices/Edit', [
            'accounts' => Account::all(),
            'company' => $company,
            'customers' => Customer::all(),
            'invoice' => ['number' => Invoice::generateInvoiceNumber(), 'company_id' => $company->id, 'items' => []],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $this->invoiceRepository->store($request->validated());

        return redirect()->route('invoices.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return Inertia::render('invoices/View', [
            'invoice' => $invoice->with(['account', 'customer', 'items'])->find($invoice->id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        return Inertia::render('invoices/Edit', [
            'accounts' => Account::all(),
            'company' => Company::first(),
            'customers' => Customer::all(),
            'invoice' => $invoice->with(['items'])->find($invoice->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $this->invoiceRepository->update($invoice, $request->validated());

        return redirect()->route('invoices.index')->with('success', 'Invoice edited successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $this->invoiceRepository->delete($invoice);

        return redirect()->route('invoices.index');
    }
}
