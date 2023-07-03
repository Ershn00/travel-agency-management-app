<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Activity>
 */
class ActivityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id'  => Company::factory(),
            'rep_id'      => User::factory()->rep(),
            'name'        => fake()->name(),
            'description' => fake()->text(),
            'start_date'  => Carbon::now(),
            'price'       => fake()->randomNumber(5),
        ];
    }
}
