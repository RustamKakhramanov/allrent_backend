<?php

namespace App\Http\Controllers\Rents;

use App\Models\Record\Rent;
use App\Models\Location\Place;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRentRequest;
use App\Http\Requests\UpdateRentRequest;
use App\Transformers\RentTransformer;
use App\Transformers\ScheduleTransformer;

class RentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Rent::class, 'rent');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRentRequest $request, Company $company, Place $place)
    {
       return fractal(Rent::create([
        'rentable_id' => $place->id,
        'rentable_type' => $place::class,
        ...$request->validated()
       ]), new RentTransformer);
    //    return fractal($place->rents()->create($request->validated()), new RentTransformer);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRentRequest  $request
     * @param  \App\Models\Record\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRentRequest $request, Rent $rent, Company $company, Place $place)
    {
       dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Record\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rent $rent)
    {
        //
    }
}
