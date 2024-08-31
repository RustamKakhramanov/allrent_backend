<?php

namespace App\Repositories\Company;

use App\Models\Location\Place;
use App\Models\Company\Company;
use App\Repositories\Repository;
use App\Repositories\Location\PlaceRepository;

class CompanyRepository extends Repository
{
    protected $model_name = Company::class;

    public function init($company)
    {
        return $company->whereHas('places');
    }

    public function afterPaginate($companies)
    {
        return $companies->map(function ($company) {
            if (user() && user()->isMember($company)) {
            } else {
                // $company->load(['places.completed_schedule']);
            }


            return $company;
        });
    }

    public static function getCompletedSchedule($schedule, $rents)
    {
        return (new PlaceRepository)->getCompletedSchedule($schedule, $rents);
    }

    public function afterGet($companies)
    {
        return $companies->map(function ($company) {
            $company->load(['books' => function ($query) {
                $query->orderBy('published_date', 'asc');
            }]);

            return $company;
        });
    }

    public function companyFilter(Company $company)
    {
        $company->places->map(
            function (Place $place) use ($company) {
                $company->load(['books' => function ($query) {
                    $query->orderBy('published_date', 'asc');
                }]);


                // if (user() && user()->isMember($company)) {
                //     $place->rents->filter(
                //         fn (Rent $item) => !user()->isMember($company)
                //     );
                // } else {
                //     $place->rents = null;
                // }

                return $place;
            }
        );

        return $company;
    }
}
