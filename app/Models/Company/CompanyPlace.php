<?php

namespace App\Models\Company;

use App\Models\Location\Place;
use App\Traits\Eloquent\CompanySearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyPlace extends Model
{
    use HasFactory, CompanySearch;

    protected $fillable = ['place_id', 'company_id'];


    public function place() {
        return $this->hasOne(Place::class);
    }

    public function company() {
        return $this->hasOne(Company::class);
    }

}
