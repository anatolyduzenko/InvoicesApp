<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanySettingsController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show()
    {
        return Inertia::render('companySettings/Form', [
            'companySettings' => Company::firstOrCreate(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:255',
            'company_email' => 'required|email',
            'company_phone' => 'required|string|max:20',
            'company_terms' => 'required|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo_path'] = $path;
        }
        Company::first()->update($validated);

        return redirect()->route('company-settings.show')
            ->with('success', 'Company settings updated successfully!');
    }
}
