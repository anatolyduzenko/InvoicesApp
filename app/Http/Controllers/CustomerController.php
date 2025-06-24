<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use App\Services\Templates\TemplateManager;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function __construct(protected CustomerRepository $customerRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('customers/List', [
            'customers' => $this->customerRepository->getAll(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(TemplateManager $templateManager)
    {
        return Inertia::render('customers/Edit', [
            'templates' => $templateManager->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $this->customerRepository->store($request->validated());

        return redirect()->route('customers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return Inertia::render('customers/View', [
            'customer' => $customer,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer, TemplateManager $templateManager)
    {
        return Inertia::render('customers/Edit', [
            'customer' => $customer,
            'templates' => $templateManager->all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $this->customerRepository->update($customer, $request->validated());

        return redirect()->route('customers.show', $customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $this->customerRepository->delete($customer);

        return redirect()->route('customers.index');
    }
}
