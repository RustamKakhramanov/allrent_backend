<?php

namespace Database\Factories\Company;

use App\Models\User;
use App\Enums\CompanyRolesEnum;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyMemberFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'company_id' => Company::factory(),
            'type' => fake()->randomElement(CompanyRolesEnum::toArrayCases()),
        ];
    }
}
