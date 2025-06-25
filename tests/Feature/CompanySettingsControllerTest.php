<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompanySettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_displays_company_settings()
    {
        $company = Company::create([
            'company_name' => 'Test Co.',
            'company_address' => '123 Street',
            'company_email' => 'info@test.com',
            'company_phone' => '+123456789',
            'company_terms' => 'Standard terms',
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('company-settings.show'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('companySettings/Form')
            ->has('companySettings', fn ($props) => $props->where('company_name', 'Test Co.')
                ->where('company_email', 'info@test.com')
                ->etc()
            )
        );
    }

    public function test_update_company_settings_without_logo()
    {
        $user = User::factory()->create();
        Session::start();

        $company = Company::create([
            'company_name' => 'Old Co.',
            'company_address' => 'Old Addr',
            'company_email' => 'old@example.com',
            'company_phone' => '123',
            'company_terms' => 'Old',
        ]);

        $response = $this->actingAs($user)->post(route('company-settings.update', $company), [
            'company_name' => 'New Co.',
            'company_address' => 'New Addr',
            'company_email' => 'new@example.com',
            'company_phone' => '999',
            'company_terms' => 'Updated terms',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('company-settings.show'));
        $this->assertDatabaseHas('companies', [
            'company_name' => 'New Co.',
            'company_email' => 'new@example.com',
        ]);
    }

    public function test_update_company_settings_with_logo()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        Session::start();

        $company = Company::create([
            'company_name' => 'Old',
            'company_address' => 'Addr',
            'company_email' => 'old@example.com',
            'company_phone' => '000',
            'company_terms' => 'Terms',
        ]);

        $file = UploadedFile::fake()->image('logo.png');

        $response = $this->actingAs($user)->post(route('company-settings.update', $company), [
            'company_name' => 'With Logo Co.',
            'company_address' => 'Addr',
            'company_email' => 'logo@example.com',
            'company_phone' => '111',
            'company_terms' => 'Terms',
            'logo' => $file,
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('company-settings.show'));

        $this->assertDatabaseHas('companies', [
            'company_email' => 'logo@example.com',
        ]);

        Storage::disk('public')->assertExists('logos/'.$file->hashName());
    }
}
