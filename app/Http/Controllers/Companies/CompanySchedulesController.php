<?php

namespace App\Http\Controllers\Companies;

use App\Models\Location\Place;
use App\Models\Company\Company;
use App\Models\Record\Schedule;
use App\Http\Controllers\Controller;
use App\Models\Company\PlaceSchedule;
use App\Transformers\ScheduleTransformer;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Requests\StorePlaceScheduleRequest;
use App\Http\Requests\UpdatePlaceScheduleRequest;

class CompanySchedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Company $company)
    {
        return fractal($company->allSchedules, new ScheduleTransformer);
    }


    public function store(StoreScheduleRequest $request, Company $company, Place $place)
    {
        $this->authorize('create', Schedule::class);

        return fractal($place->schedules()->create($request->validated()), new ScheduleTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company\PlaceSchedule  $placeSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $placeSchedule)
    {
        return fractal($placeSchedule, new ScheduleTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePlaceScheduleRequest  $request
     * @param  \App\Models\Company\PlaceSchedule  $placeSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateScheduleRequest $request, Schedule $placeSchedule)
    {
        $this->authorize('update', Schedule::class);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company\PlaceSchedule  $placeSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $placeSchedule)
    {
        //
    }
}
