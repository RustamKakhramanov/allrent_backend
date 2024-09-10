<?php

namespace App\Http\Controllers;

use App\Enums\HomePageEnum;
use App\Repositories\HomeRepository;
use App\Transformers\HomeTransformer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $case = HomePageEnum::Place;

        return fractal(
            HomeRepository::parse($case, $request->all()),
            new HomeTransformer
        )->parseIncludes($case->getDataIncludes());
    }
}
