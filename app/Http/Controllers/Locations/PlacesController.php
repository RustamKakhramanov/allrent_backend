<?php

namespace App\Http\Controllers\Locations;

use App\Models\Location\Place;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;
use App\Transformers\PlaceTransformer;

class PlacesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePlaceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePlaceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company, Place $place)
    {
      return fractal($place, new PlaceTransformer)->parseIncludes([
        'free_today_schedule', 'images', 'company', 'abilities', 'reviews', 'contacts'
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePlaceRequest  $request
     * @param  \App\Models\Location\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePlaceRequest $request, Place $place)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function destroy(Place $place)
    {
        //
    }
}
