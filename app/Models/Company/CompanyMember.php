<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyMember extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'company_id', 'type'];

    public function scopeWhereCompany(Builder $builder, $company) {
        return $builder->whereCompanyId(
            $company instanceof Company ? $company->id : $company
        );
    }
}
