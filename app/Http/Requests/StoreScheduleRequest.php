<?php

namespace App\Http\Requests;

use App\Enums\TimeEnum;
use App\Enums\ScheduleTypeEnum;
use App\Models\Record\Schedule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'schedule' => 'array|required',
            'schedule.*' => 'integer',
            'type' => ['sometimes', new Enum(ScheduleTypeEnum::class)],
            'date' => [Rule::requiredIf(fn () => $this->type && $this->type !== ScheduleTypeEnum::Default()), 'integer']
        ];
    }

    public function withValidator(Validator $validator)
    {

        $validator->after(function (Validator $validator) {
            $existError =  function() {
                if (request('type', ScheduleTypeEnum::Default()) !== ScheduleTypeEnum::Default()) {
                    switch (request('type')) {
                        case ScheduleTypeEnum::Month():
                            $queryName = 'whereMonth';
    
                        default:
                            $queryName = 'whereDay';
                    }
    
                    return Schedule::query()->whereType($this->type)->$queryName('date', request('date'))->exists();
                } else {
                    return Schedule::query()->whereType(ScheduleTypeEnum::Default())->exists();
                }
            };
            
            if($existError()){
                $validator->errors()->add('type', $this->type.' schedule exists in this place');
            }
        });
    }
}
