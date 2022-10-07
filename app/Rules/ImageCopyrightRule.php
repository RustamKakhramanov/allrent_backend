<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ImageCopyrightRule implements Rule
{
    /**
     * @var string|null|Rule
     */
    private $image_rules;

    /**
     * @var string
     */
    private string $message;
    private array $additional_rules;

    /**
     * Create a new rule instance.
     *
     * @param $image_rules
     * @param $additional_rules
     */
    public function __construct($image_rules, $additional_rules = [])
    {
        $this->image_rules = $image_rules;
        $this->additional_rules = $additional_rules;
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
        $validator = Validator::make((array) $value, array_merge([
            'image' => $this->image_rules,
            'description' => 'nullable|string'
        ], $this->additional_rules));

        if ($validator->passes()) {
            return true;
        }

        $this->message = $validator->errors()->first();

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
