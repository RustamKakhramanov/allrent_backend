<?php

namespace App\Transformers;

use App\DTOs\HomeDTO;
use League\Fractal\TransformerAbstract;

class HomeTransformer extends TransformerAbstract
{
    

    protected array $availableIncludes = [
        'companies', 'company', 'html', 
    ];


    public function transform(HomeDTO $home)
    {
        return [
            'type' => $home->type
        ];
    }

    public function includeCompany(HomeDTO $home){
        return $this->item($home->company, new CompanyTransformer);
    }

    public function includeCompanies(HomeDTO $home){
        return $this->collection($home->companies, new CompanyTransformer);
    }

    public function includeHtml(HomeDTO $home){
        return $this->primitive($home->html);
    }
}
