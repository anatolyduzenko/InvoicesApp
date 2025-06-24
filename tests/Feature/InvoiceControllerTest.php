<?php 

namespace Tests\Feature;

use App\Models\User;
use App\Models\Account;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_store_redirects_to_invoices_list()
    {
        $user = User::factory()->create();
        Session::start();
        $this->actingAs($user);

        $company = Company::create([
            'company_name' => 'Acme Inc.',
            'company_address' => '123 Main St',
            'company_email' => 'info@acme.com',
            'company_phone' => '123456',
            'company_terms' => 'Terms',
        ]);

        $account = Account::create([
            'intermediary' => 'Bank A',
            'institution' => 'Inst',
            'beneficiary' => 'John Doe',
            'account' => 'ACC123',
            'currency' => 'USD',
        ]);

        $customer = Customer::create([
            'name' => 'Client A',
            'country' => 'US',
            'address' => 'Street 1',
            'email' => 'client@example.com',
            'phone' => '555-111',
            'currency' => 'USD',
            'template_name' => 'default',
        ]);

        $response = $this->post(route('invoices.store'), [
            'customer_id' => $customer->id,
            'account_id' => $account->id,
            'company_id' => $company->id,
            'number' => 'INV-001',
            'currency' => 'USD',
            'issue_date' => Carbon::now(),
            'due_date' => Carbon::now(),
            '_token' => csrf_token(),
            'total_amount' => 100,
            'items' => [
                [
                    'id' => 1000000000021000021, 
                    'description' => 'Test Item', 
                    'unit' => 'hour', 
                    'qty' => 2, 
                    'price' => 50,
                    'amount' => 100
                ]
            ],
        ]);

        $response->assertRedirect(route('invoices.index'));

        $this->assertDatabaseHas('invoices', ['number' => 'INV-001']);
    }

    public function test_create_displays_invoice_form()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $company = Company::create([
            'company_name' => 'Acme Inc.',
            'company_address' => '123 Main St',
            'company_email' => 'info@acme.com',
            'company_phone' => '123456',
            'company_terms' => 'Terms',
        ]);

        $account = Account::create([
            'intermediary' => 'Bank A',
            'institution' => 'Inst',
            'beneficiary' => 'John Doe',
            'account' => 'ACC123',
            'currency' => 'USD',
        ]);

        $customer = Customer::create([
            'name' => 'Client A',
            'country' => 'US',
            'address' => 'Street 1',
            'email' => 'client@example.com',
            'phone' => '555-111',
            'currency' => 'USD',
            'template_name' => 'default',
        ]);

        $response = $this->get(route('invoices.create'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) =>
            $page->component('invoices/Edit')
                ->has('company')
                ->has('accounts')
                ->has('customers')
                ->has('invoice')
        );
    }

    public function test_edit_displays_invoice_with_relations()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $invoice = Invoice::create([
            'customer_id' => Customer::create([
                'name' => 'Client A',
                'country' => 'US',
                'address' => 'Street 1',
                'email' => 'client@example.com',
                'phone' => '555-111',
                'currency' => 'USD',
                'template_name' => 'default',
            ])->id,
            'account_id' => Account::create([
                'intermediary' => 'Bank A',
                'institution' => 'Inst',
                'beneficiary' => 'John Doe',
                'account' => 'ACC123',
                'currency' => 'USD',
            ])->id,
            'company_id' => Company::create([
                'company_name' => 'Acme Inc.',
                'company_address' => '123 Main St',
                'company_email' => 'info@acme.com',
                'company_phone' => '123456',
                'company_terms' => 'Terms',
            ])->id,
            'number' => 'INV-001',
            'currency' => 'USD',
            'total_amount' => '1000',
            'issue_date' => Carbon::now(),
            'due_date' => Carbon::now(),
        ]);

        $response = $this->get(route('invoices.edit', $invoice));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) =>
            $page->component('invoices/Edit')
                ->has('invoice')
                ->has('accounts')
                ->has('company')
                ->has('customers')
        );
    }

    public function test_destroy_removes_invoice()
    {
        $user = User::factory()->create();
        Session::start();
        $this->actingAs($user);

        $invoice = Invoice::create([
            'customer_id' => Customer::create([
                'name' => 'Client A',
                'country' => 'US',
                'address' => 'Street 1',
                'email' => 'client@example.com',
                'phone' => '555-111',
                'currency' => 'USD',
                'template_name' => 'default',
            ])->id,
            'account_id' => Account::create([
                'intermediary' => 'Bank A',
                'institution' => 'Inst',
                'beneficiary' => 'John Doe',
                'account' => 'ACC123',
                'currency' => 'USD',
            ])->id,
            'company_id' => Company::create([
                'company_name' => 'Acme Inc.',
                'company_address' => '123 Main St',
                'company_email' => 'info@acme.com',
                'company_phone' => '123456',
                'company_terms' => 'Terms',
            ])->id,
            'number' => 'INV-001',
            'currency' => 'USD',
            'total_amount' => '1000',
            'issue_date' => Carbon::now(),
            'due_date' => Carbon::now(),
        ]);

        $response = $this->post(route('invoices.destroy', $invoice), [
            '_token' => csrf_token(),
            '_method' => 'DELETE',
        ]);

        $response->assertRedirect(route('invoices.index'));
        $this->assertSoftDeleted('invoices', ['id' => $invoice->id]);
    }

    public function test_update_modifies_invoice_successfully()
    {
        $user = User::factory()->create();
        Session::start();
        $this->actingAs($user);

        $customer = Customer::create([
            'name' => 'Client A',
            'country' => 'US',
            'address' => 'Street 1',
            'email' => 'client@example.com',
            'phone' => '555-111',
            'currency' => 'USD',
            'template_name' => 'default',
        ]);

        $account = Account::create([
            'intermediary' => 'Bank A',
            'institution' => 'Inst',
            'beneficiary' => 'John Doe',
            'account' => 'ACC123',
            'currency' => 'USD',
        ]);

        $company = Company::create([
            'company_name' => 'Acme Inc.',
            'company_address' => '123 Main St',
            'company_email' => 'info@acme.com',
            'company_phone' => '123456',
            'company_terms' => 'Terms',
        ]);

        $invoice = Invoice::create([
            'customer_id' => $customer->id,
            'account_id' => $account->id,
            'company_id' => $company->id,
            'number' => 'INV-001',
            'total_amount' => 1000,
            'issue_date' => now(),
            'due_date' => now()->addDays(7),
        ]);

        $response = $this->put(route('invoices.update', $invoice), [
            'customer_id' => $customer->id,
            'account_id' => $account->id,
            'company_id' => $company->id,
            'number' => 'INV-UPDATED',
            'total_amount' => 2000,
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(10)->toDateString(),
            '_token' => csrf_token(),
            'items' => [
                [
                    'id' => 1000000000021000021, 
                    'description' => 'Test Item', 
                    'unit' => 'hour', 
                    'qty' => 2, 
                    'price' => 50,
                    'amount' => 100
                ]
            ],
        ]);

        $response->assertRedirect(route('invoices.index'));

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'number' => 'INV-UPDATED',
            'total_amount' => 2000,
        ]);
    }

}
