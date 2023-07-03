<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\Activity\StoreActivityRequest;
use App\Http\Requests\Activity\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CompanyActivityController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(Company $company): View
    {
        $this->authorize('viewAny', $company);

        $company->load('activities');

        return view('companies.activities.index', compact('company'));
    }

    /**
     * @throws AuthorizationException
     */
    public function create(Company $company): View
    {
        $this->authorize('create', $company);

        $reps = User::where('company_id', $company->id)
            ->where('role_id', Role::REP->value)
            ->pluck('name', 'id');

        return view('companies.activities.create', compact('reps', 'company'));
    }

    /**
     * @throws AuthorizationException
     */
    public function store(StoreActivityRequest $request, Company $company): RedirectResponse
    {
        $this->authorize('create', $company);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->storePublicly('activities', 'public');
        }

        $activity = Activity::create($request->validated() + [
                'company_id' => $company->id,
                'photo' => $path ?? null,
            ]);

        return to_route('companies.activities.index', $company);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Company $company, Activity $activity): View
    {
        $this->authorize('update', $company);

        $reps = User::where('company_id', $company->id)
            ->where('role_id', Role::REP->value)
            ->pluck('name', 'id');

        return view('companies.activities.edit', compact('reps', 'activity', 'company'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpdateActivityRequest $request, Company $company, Activity $activity): RedirectResponse
    {
        $this->authorize('update', $company);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('activities', 'public');
            if ($activity->photo) {
                Storage::disk('public')->delete($activity->photo);
            }
        }

        $activity->update($request->validated() + [
                'photo' => $path ?? $activity->photo,
            ]);

        return to_route('companies.activities.index', $company);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Company $company, Activity $activity): RedirectResponse
    {
        $this->authorize('delete', $company);

        $activity->delete();

        return to_route('companies.activities.index', $company);
    }
}
