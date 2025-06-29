<?php

namespace App\Repositories;

use App\Interfaces\InvoiceRepositoryInterface;
use App\Models\Invoice;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

final class InvoiceRepository implements InvoiceRepositoryInterface
{
    /**
     * Stores a new invoice instance.
     */
    public function store(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            $invoice = Invoice::create($data);

            if (! empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    $invoice->items()->create($item);
                }
            }

            return $invoice->load('items');
        });
    }

    /**
     * Updates invoice instance.
     */
    public function update(Invoice $invoice, array $data): Invoice
    {
        return DB::transaction(function () use ($invoice, $data) {
            $invoice->update($data);

            $incomingIds = collect($data['items'])->pluck('id')->filter()->all();

            $invoice->items()->whereNotIn('id', $incomingIds)->delete();

            foreach ($data['items'] as $item) {
                $invoice->items()->updateOrCreate(
                    ['id' => $item['id'] > 10000000000 ? null : $item['id']],
                    Arr::except($item, ['id']) + ['invoice_id' => $invoice->id]
                );
            }

            return $invoice->load('items');
        });
    }

    public function delete(Invoice $invoice): void
    {
        DB::transaction(function () use ($invoice) {
            $invoice->items()->delete();
            $invoice->delete();
        });
    }

    public function clone(Invoice $invoice): Invoice
    {
        return DB::transaction(function () use ($invoice) {
            $original = Invoice::with('items')->findOrFail($invoice->id);

            $newInvoice = $original->replicate();
            $newInvoice->number = Invoice::generateInvoiceNumber();
            $newInvoice->status = 'new';
            $newInvoice->save();

            foreach ($original->items as $item) {
                $newInvoice->items()->create($item->replicate()->toArray());
            }

            return $newInvoice->load(['account', 'customer', 'items']);
        });
    }

    public function getAll(array $filters = [], array $with = [], $perPage = 20): LengthAwarePaginator
    {
        $query = Invoice::with($with)->latest();

        if (isset($filters['status'])) {
            $query->status($filters['status']);
        }

        if (isset($filters['account_id'])) {
            $query->forCustomer($filters['customer_id']);
        }

        if (isset($filters['customer_id'])) {
            $query->forCustomer($filters['customer_id']);
        }

        if (isset($filters['from_date'])) {
            $query->fromDate($filters['from_date']);
        }

        if (! empty($filters['with_trashed'])) {
            $query->withTrashed();
        }

        return $query->paginate($perPage);
    }
}
