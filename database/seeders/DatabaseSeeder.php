<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Throwable;
use App\Models\User;
use App\Models\Review;
use App\Models\Record\Price;
use App\Models\Location\Place;
use App\Models\Company\Company;
use App\Models\Record\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Specialist\Speciality;
use App\Enums\RolesAndPermissionsEnum;
use App\Console\Commands\CreateAdminCommand;
use App\Models\Specialist\SpecialistProfile;
use App\Console\Commands\UserCreationCommand;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();

            $this->call(RolesAndPermissionsSeeder::class);

            if (is_local()) {
                $user = User::factory()
                    ->has(
                        SpecialistProfile::factory()
                            ->freelancers()
                            ->hasAttached(Speciality::factory()->count(1), ['is_main' => true]),
                        'profiles'
                    )
                    ->create();

                Company::factory()
                    ->hasMembers(3)
                    ->has(
                        Place::factory()->has(
                            Schedule::factory()->state(
                                fn ($attributes, Place $place) => ['company_id' => $place->company_id]
                            )->has(Price::factory())->for($user)
                        )->has(
                            Review::factory()
                                ->count(5)
                                ->for($user, 'reviewer')

                        )->count(3)
                    )

                    ->count(3)
                    ->create();
            }

            // app(CreateAdminCommand::class)->handle();
            
            $this->call(AdminSeeder::class);

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            throw  new HttpException($exception->getCode(), $exception->getMessage());
        }
    }
}

// ->hasAttached(
//     Speciality::factory()->count(1),
//     ['is_main' => true]
// )