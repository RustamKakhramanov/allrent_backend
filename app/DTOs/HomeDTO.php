<?php

namespace App\DTOs;

use App\DTOs\DTO;
use App\Enums\HomePageEnum;
use App\Models\Company\Company;
use App\Transformers\PlaceTransformer;
use App\Transformers\CompanyTransformer;
use App\Repositories\Location\PlaceRepository;
use App\Repositories\Company\CompanyRepository;
use App\Transformers\HomeTransformer;

class HomeDTO extends DTO
{
    protected string $type = 'company';
    
    protected ?Company $company;
    protected ?array $companies;
    protected ?string $html;
    protected ?array $meta;
    protected ?array $images;
    protected ?array $forms;
    protected ?array $offers;
    protected ?array $vacansies;


}
