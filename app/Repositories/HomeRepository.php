<?php

namespace App\Repositories;

use App\DTOs\HomeDTO;
use App\Enums\HomePageEnum;
use App\Repositories\Company\CompanyRepository;
use Illuminate\Support\Collection;

class HomeRepository
{
    private HomePageEnum $case;
    private Collection $data;

    public static function parse(HomePageEnum $type): HomeDTO
    {
        return (new static($type))->getAllData();
    }

    public function __construct(HomePageEnum $type)
    {
        $this->case = $type;
        $this->data = collect();
    }


    public function getAllData()
    {

        switch ($this->case->value) {
            case HomePageEnum::Company():
                $this->setCompanyData();
                break;
        }
    
        return HomeDTO::make($this->data);
    }

    public function setCompanyData()
    {
        $this->data = $this->data->merge(['company' => CompanyRepository::first()]);
    }
}
