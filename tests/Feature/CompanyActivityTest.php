<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_owner_can_view_activities_page()
    {
        $company = Company::factory()->create();
        $user = User::factory()->companyOwner()->create(['company_id' => $company->id]);

        $response = $this->actingAs($user)->get(route('companies.activities.index', $company, $company));

        $response->assertOk();
    }

    public function test_company_owner_can_see_only_his_companies_activities()
    {
        $company = Company::factory()->create();
        $user = User::factory()->companyOwner()->create(['company_id' => $company->id]);
        $activity = Activity::factory()->create(['company_id' => $company->id]);
        $activity2 = Activity::factory()->create();

        $response = $this->actingAs($user)->get(route('companies.activities.index', $company));

        $response->assertSeeText($activity->name)
            ->assertDontSeeText($activity2->name);
    }
}
