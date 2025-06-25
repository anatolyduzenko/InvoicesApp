<?php

namespace Tests\Feature\Controllers;

use App\Models\Account;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use App\Services\Templates\TemplateManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class InvoiceViewControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
        Session::start();
    }

    public function test_show_displays_invoice_view()
    {
        $invoice = Invoice::factory()
            ->has(Customer::factory())
            ->has(Account::factory())
            ->has(Company::factory())
            ->has(InvoiceItem::factory()->count(2), 'items')
            ->create();

        $templateManager = Mockery::mock(TemplateManager::class);
        $templateManager->shouldReceive('getViewByKey')
            ->with('basic')
            ->andReturn('invoices.basic');

        $this->app->instance(TemplateManager::class, $templateManager);

        $response = $this->get(route('invoices.preview', $invoice));

        $response->assertStatus(200);
        $response->assertViewIs('invoices.basic');
        $response->assertViewHas('invoice');
    }

    public function test_download_generates_pdf_and_returns_file()
    {
        Storage::fake('public');

        $invoice = Invoice::factory()
            ->has(Customer::factory(['template_name' => 'basic']))
            ->has(Account::factory())
            ->has(Company::factory())
            ->has(InvoiceItem::factory()->count(1), 'items')
            ->create();

        $templateManager = Mockery::mock(TemplateManager::class);
        $templateManager->shouldReceive('getViewByKey')
            ->with('basic')
            ->andReturn('invoices.basic');

        $this->app->instance(TemplateManager::class, $templateManager);

        Http::fake([
            '*' => Http::response('%PDF-1.4 simulated pdf content%', 200),
        ]);

        $response = $this->get(route('invoices.download', $invoice));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertDownload();
    }
}
