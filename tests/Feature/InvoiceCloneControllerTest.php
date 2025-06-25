<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class InvoiceCloneControllerTest extends TestCase
{
    public function test_invoice_clone_form_displays_with_cloned_invoice()
    {
        $user = User::factory()->create();
        Session::start();
        $this->actingAs($user);

        // Prepare related data
        $company = Company::create([
            'company_name' => 'Acme Inc.',
            'company_address' => '123 Main St',
            'company_email' => 'info@acme.com',
            'company_phone' => '123456',
            'company_terms' => 'Standard terms',
        ]);

        $account = Account::create([
            'intermediary' => 'Bank X',
            'institution' => 'Finance Corp',
            'beneficiary' => 'John Doe',
            'account' => 'ACC001',
            'currency' => 'USD',
        ]);

        $customer = Customer::create([
            'name' => 'Jane Smith',
            'country' => 'US',
            'address' => '456 Market St',
            'email' => 'jane@example.com',
            'phone' => '555-9876',
            'currency' => 'USD',
            'template_name' => 'default',
        ]);

        $invoice = Invoice::create([
            'company_id' => $company->id,
            'account_id' => $account->id,
            'customer_id' => $customer->id,
            'number' => 'INV-001',
            'total_amount' => 500,
            'issue_date' => now(),
            'due_date' => now()->addDays(7),
            'items' => [
                [
                    'id' => 1000000000021000021,
                    'description' => 'Test Item',
                    'unit' => 'hour',
                    'qty' => 2,
                    'price' => 50,
                    'amount' => 100,
                ],
            ],
        ]);

        $response = $this->get(route('invoices.clone', $invoice));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->component('invoices/Edit')
            ->has('accounts')
            ->has('company')
            ->has('customers')
            ->has('invoice', fn ($props) => $props->where('number', 'INV-00002')->etc()
            )
        );
    }
}
