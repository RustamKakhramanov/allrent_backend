<?php

namespace App\Http\Controllers;

use App\DTOs\HomeDTO;
use App\Enums\HomePageEnum;
use GuzzleHttp\Psr7\Request;
use App\Models\Location\Place;
use App\Models\Company\Company;
use App\Repositories\HomeRepository;
use App\Transformers\PlaceTransformer;
use App\Transformers\CompanyTransformer;
use App\Repositories\Location\PlaceRepository;
use App\Repositories\Company\CompanyRepository;
use App\Transformers\HomeTransformer;

class HomeController extends Controller
{
    public function index()
    {
        $case = HomePageEnum::Company;

        return fractal(
            HomeRepository::parse($case),
            new HomeTransformer
        )->parseIncludes($case->getDataIncludes());
    }
}
