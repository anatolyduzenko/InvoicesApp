<?php

namespace App\Interfaces;

use App\Models\Invoice;
use Illuminate\Pagination\LengthAwarePaginator;

interface InvoiceRepositoryInterface
{
    public function store(array $data): Invoice;

    public function update(Invoice $invoice, array $data): Invoice;

    public function delete(Invoice $invoice): void;

    public function clone(Invoice $invoice): Invoice;

    public function getById(int $id, array $with = []): ?Invoice;

    public function getAll(array $filters = [], array $with = []): LengthAwarePaginator;
}
