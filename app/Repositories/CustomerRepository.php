<?php

namespace App\Repositories;

use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class CustomerRepository implements CustomerRepositoryInterface
{
    /**
     * Stores a new customer instance.
     */
    public function store(array $data): Customer
    {
        return DB::transaction(function () use ($data) {
            return Customer::create($data);
        });
    }

    /**
     * Updates customer instance.
     */
    public function update(Customer $customer, array $data): Customer
    {
        return DB::transaction(function () use ($customer, $data) {
            $customer->update($data);

            return $customer;
        });
    }

    /**
     * Deletes customer instance.
     */
    public function delete(Customer $customer): void
    {
        DB::transaction(function () use ($customer) {
            foreach ($customer->invoices as $invoice) {
                (new InvoiceRepository)->delete($invoice);
            }
            $customer->delete();
        });
    }

    /**
     * Retrieves a list of customers with filters.
     */
    public function getAll(array $filters = [], array $with = [], $perPage = 20): LengthAwarePaginator
    {
        $query = Customer::with($with)->latest();

        if (! empty($filters['with_trashed'])) {
            $query->withTrashed();
        }

        return $query->paginate($perPage);
    }
}
