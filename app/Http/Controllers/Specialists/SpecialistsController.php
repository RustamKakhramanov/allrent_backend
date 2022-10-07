<?php

namespace App\Http\Controllers\Specialists;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSpecialistProfileRequest;
use App\Http\Requests\UpdateSpecialistProfileRequest;
use App\Models\Specialist\SpecialistProfile;

class SpecialistsController extends Controller
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
     * @param  \App\Http\Requests\StoreSpecialistProfileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSpecialistProfileRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Specialist\SpecialistProfile  $specialistProfile
     * @return \Illuminate\Http\Response
     */
    public function show(SpecialistProfile $specialistProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSpecialistProfileRequest  $request
     * @param  \App\Models\Specialist\SpecialistProfile  $specialistProfile
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSpecialistProfileRequest $request, SpecialistProfile $specialistProfile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Specialist\SpecialistProfile  $specialistProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(SpecialistProfile $specialistProfile)
    {
        //
    }
}
