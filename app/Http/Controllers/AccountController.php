<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Models\Account;
use Inertia\Inertia;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('accounts/List', [
            'accounts' => Account::all(),
        ]);
    }

    /**
     * Display a form to create the resource.
     */
    public function create()
    {
        return Inertia::render('accounts/Edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        Account::create($request->validated());

        return redirect()->route('accounts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        return Inertia::render('accounts/View', [
            'account' => $account,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        return Inertia::render('accounts/Edit', [
            'account' => $account,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        $account->update($request->validated());

        return redirect()->route('accounts.show', $account);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return redirect()->route('accounts.index');
    }
}
