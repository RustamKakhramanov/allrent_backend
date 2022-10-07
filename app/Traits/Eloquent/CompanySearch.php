<?php

namespace App\Traits\Eloquent;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static forCompany(Company|int $company)
 */
trait CompanySearch
{

    public function scopeWhereCompany(Builder $builder, $company)
    {
        return $builder->whereCompanyId(
            $company instanceof Company ? $company->id : $company
        );
    }
}
