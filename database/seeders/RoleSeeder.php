<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'Administrator']);
        Role::create(['name' => 'Company Owner']);
        Role::create(['name' => 'Customer']);
        Role::create(['name' => 'Guide']);
    }
}
