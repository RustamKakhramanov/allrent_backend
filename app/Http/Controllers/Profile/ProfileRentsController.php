<?php

namespace App\Http\Controllers\Profile;

use App\Models\Record\Rent;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRentRequest;
use App\Transformers\RentTransformer;

class ProfileRentsController extends Controller
{

    public function index()
    {
      return fractal(auth()->user()->rents, new RentTransformer);
    }

    public function show(Rent $rent)
    {
        if($rent->user_id !== auth()->user()->id){
            abort(404);
        }

      return fractal($rent, new RentTransformer)->parseIncludes(['rentable','rentable.images', 'specialist']);
    }
 

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \App\Http\Requests\StoreRentRequest  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(StoreRentRequest $request, Company $company, Place $place)
    // {
    //    return fractal($place->rents()->create($request->validated()), new RentTransformer);
    // }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRentRequest  $request
     * @param  \App\Models\Record\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRentRequest $request, Rent $rent)
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
        $rent->delete();
    }
}
