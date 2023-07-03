<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\User\StoreRepRequest;
use App\Http\Requests\User\UpdateRepRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyRepController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(Company $company): View
    {
        $this->authorize('viewAny', $company);

        $reps = $company->users()->where('role_id', Role::REP->value)->get();

        return view('companies.reps.index', compact('company', 'reps'));
    }

    /**
     * @throws AuthorizationException
     */
    public function create(Company $company): View
    {
        $this->authorize('create', $company);

        return view('companies.reps.create', compact('company'));
    }

    /**
     * @throws AuthorizationException
     */
    public function store(StoreRepRequest $request, Company $company): RedirectResponse
    {
        $this->authorize('create', $company);

        $company->users()->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role_id' => Role::REP->value,
        ]);

        return to_route('companies.reps.index', $company);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Company $company, User $rep): View
    {
        $this->authorize('update', $company);

        return view('companies.reps.edit', compact('company', 'rep'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpdateRepRequest $request, Company $company, User $rep): RedirectResponse
    {
        $this->authorize('update', $company);

        $rep->update($request->validated());

        return to_route('companies.reps.index', $company);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Company $company, User $rep): RedirectResponse
    {
        $this->authorize('delete', $company);

        $rep->delete();

        return to_route('companies.reps.index', $company);
    }
}
