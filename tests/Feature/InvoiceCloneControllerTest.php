<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class InvoiceCloneControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_clone_form_displays_with_cloned_invoice()
    {
        $user = User::factory()->create();
        Session::start();
        $this->actingAs($user);

        $invoice = Invoice::factory()->create();

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
