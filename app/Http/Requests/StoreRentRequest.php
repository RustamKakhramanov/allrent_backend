<?php

namespace App\Http\Requests;

use App\Enums\TimeEnum;
use App\Enums\CurrencyEnum;
use Illuminate\Support\Carbon;
use App\Enums\ScheduleTypeEnum;
use App\Models\Location\Place;
use App\Models\Record\Schedule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreRentRequest extends FormRequest
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
            'specialist_profile_id' => 'exists:specialist_profiles,id',
            'detail' => 'nullable|array',
            'amount' => 'decimal:0,4|required',
            'currency' => [Rule::in(CurrencyEnum::toArrayCases())],
            'scheduled_at' => 'required|date',
            'scheduled_end_at' => 'required|date',
        ];
    }

    public function withValidator(Validator $validator)
    {
        // $validator->after(function (Validator $validator) {
        //     try {
        //         $from = cparse($this->scheduled_at);
        //         $to = cparse($this->scheduled_end_at);

        //         if (
        //             Place::findBySlug($this->place)->rents()
        //             ->whereBetween('scheduled_at', [$from, $to])
        //             ->orWhere(fn ($q) => $q->whereBetween('scheduled_end_at', [$from, $to]))
        //             ->exists()
        //         ) {
        //             $validator->errors()->add('scheduled_at', 'Is rented');
        //         }
        //     } catch (\Exception) {
        //         $validator->errors()->add('scheduled_at', 'validation.rented');
        //     }
        // });
    }

}
