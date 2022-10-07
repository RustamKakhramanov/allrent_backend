<?php

namespace App\Http\Requests;

use Illuminate\Support\Carbon;
use App\Enums\ScheduleTypeEnum;
use App\Enums\TimeEnum;
use App\Models\Record\Schedule;
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
            'scheduled_at' => 'required|timestamp',
            'scheduled_end_at' => 'required|timestamp',
        ];
    }

    public function withValidator(Validator $validator)
    {

        $validator->after(function (Validator $validator) {
            $from = Carbon::parse((int) request('scheduled_at'));
            $to = Carbon::parse((int) request('scheduled_end_at'));

            if (request()->place->rents()->whereBetween('scheduled_at', [$from, $to])->exists()) {
                $validator->errors()->add('scheduled_at', 'Scheduled_at is rented');
            }

            if (request()->place->rents()->whereBetween('scheduled_end_at', [$from, $to])->exists()) {
                $validator->errors()->add('scheduled_end_at', 'Scheduled_end_at is rented');
            }
        });
    }
}
