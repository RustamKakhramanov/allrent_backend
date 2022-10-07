<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Record\Rent;
use App\Models\Location\Place;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRentRequest extends StoreRentRequest
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
        return [array_merge(parent::rules(), [
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date',
        ])];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $r_rent = request()->route('rent');
            $rent = is_string($r_rent) ? Rent::find($r_rent) : $r_rent;
            $r_place = request()->route('place');
            $place = is_string($r_place) ? Place::findBySlug($r_place) : $r_place;

            $from = Carbon::parse((int) request('scheduled_at'));
            $to = Carbon::parse((int) request('scheduled_end_at'));
            $r_query = $place->rents()->where('id', '!=', $rent->id);

            if ($r_query->whereBetween('scheduled_at', [$from, $to])->exists()) {
                $validator->errors()->add('scheduled_at', 'Scheduled_at is rented');
            }

            if ($r_query->whereBetween('scheduled_end_at', [$from, $to])->exists()) {
                $validator->errors()->add('scheduled_end_at', 'Scheduled_end_at is rented');
            }

            if (request('specialist_profile_id') && !$rent->user->profiles->where('id', request('specialist_profile_id'))->first()) {
                $validator->errors()->add('scheduled_end_at', 'User not has profile with id ' . request('specialist_profile_id'));
            }
        });
    }
}
