<?php


namespace App\Rules;


use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class DatetimeOrTimestamp implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
            if ( (string) (int) $value === $value ) {
                return true;
            } else {
                return \DateTime::createFromFormat('Y-m-d H:i:s', $value) !== false;
            }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute' . ' ' . __('Must be written in datetime or timestamp format');
    }
}
