<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Models\Account;
use App\Repositories\AccountRepository;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function __construct(protected AccountRepository $accountRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('accounts/List', [
            'accounts' => $this->accountRepository->getAll(),
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
        $this->accountRepository->store($request->validated());

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
        $this->accountRepository->update($account, $request->validated());

        return redirect()->route('accounts.show', $account);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $this->accountRepository->delete($account);

        return redirect()->route('accounts.index');
    }
}
