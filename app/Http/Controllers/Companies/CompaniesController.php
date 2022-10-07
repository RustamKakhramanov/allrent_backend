<?php

namespace App\Http\Controllers\Companies;

use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use App\Transformers\CompanyTransformer;
use App\Transformers\SuccessTransformer;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Repositories\Company\CompanyRepository;

class CompaniesController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return fractal(CompanyRepository::withoutInit()->paginate(15), new CompanyTransformer)->parseIncludes('places');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCompanyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyRequest $request)
    {
        $this->authorize('create', Company::class);

        return fractal(Company::create($request->validated(), new CompanyTransformer));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return fractal($company, (new CompanyTransformer))->parseIncludes(
            user() && user()->isOwner($company) || user() && user()->isAdmin()  ? ['places', 'members'] : []
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCompanyRequest  $request
     * @param  \App\Models\Company\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $this->authorize('update', Company::class);

        return fractal($company->update($request->validated()), new SuccessTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $this->authorize('delete', Company::class);

        return fractal($company->delete(), new SuccessTransformer);
    }
}
