<?php

namespace App\Repositories;

use App\DTOs\HomeDTO;
use App\Enums\HomePageEnum;
use App\Repositories\Company\CompanyRepository;
use App\Repositories\Location\PlaceRepository;
use Illuminate\Support\Collection;

class HomeRepository
{
    private Collection $data;
    protected string $type;

    public static function parse(HomePageEnum $type): HomeDTO
    {
        return (new static($type))->getAllData();
    }

    public function __construct(HomePageEnum $type)
    {
        $this->type = $type->value;
        $this->data = collect();
    }


    public function getAllData()
    {
        switch ($this->type) {
            case HomePageEnum::Company():
                $this->setCompanyData();
                break;
            case HomePageEnum::Place():
                $this->setPlaceData();
                break;
        }

        return HomeDTO::make([...$this->data, 'type' => $this->type]);
    }

    protected function setCompanyData()
    {
        $this->data = $this->data->merge(['company' => CompanyRepository::first()]);
    }

    protected function setPlaceData()
    {
        $this->data = $this->data->merge(['place' => PlaceRepository::first()]);
    }
}
