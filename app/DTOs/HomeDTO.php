<?php

namespace App\DTOs;

use App\DTOs\DTO;
use App\Enums\HomePageEnum;
use App\Models\Location\Place;
use App\Models\Company\Company;
use App\Transformers\HomeTransformer;
use App\Transformers\PlaceTransformer;
use App\Transformers\CompanyTransformer;
use App\Repositories\Location\PlaceRepository;
use App\Repositories\Company\CompanyRepository;

class HomeDTO extends DTO
{
    protected string $type = 'company';
    
    protected ?object $data;
    protected ?Place $place;
    protected ?Company $company;
    protected ?array $companies;
    protected ?string $html;
    protected ?array $meta;
    protected ?array $images;
    protected ?array $forms;
    protected ?array $offers;
    protected ?array $vacansies;


}
