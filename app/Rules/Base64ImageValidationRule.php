<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Base64ImageValidationRule implements Rule
{
    private Collection $extensions;

    /**
     * Create a new rule instance.
     *
     * @param string ...$extensions
     */
    public function __construct(string ...$extensions)
    {
        $this->extensions = new Collection($extensions);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) : bool
    {
        $img = imagecreatefromstring(base64_decode(Str::after($value, 'base64,')));

        if (!$img) {
            return false;
        }

        imagepng($img, 'tmp.png');
        $info = getimagesize('tmp.png');

        unlink('tmp.png');

        if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
            return true;
        }

        return false;
    }
//    public function passes($attribute, $value) : bool
//    {
//        fwrite(
//            $tmpFile = tmpfile(),
//            base64_decode(Str::after($value, 'base64,'))
//        );
//
//        $uploadedFile = new UploadedFile(
//            stream_get_meta_data($tmpFile)['uri'],
//            'image',
//            'text/plain',
//            null,
//            false,
//        );
//
//        $extensions = $this->extensions->filter(function ($extension) use ($value) {
//            return Str::startsWith($value, "data:image/{$extension};base64,");
//        });
//
//        if ($extensions->isEmpty()) {
//            return false;
//        }
//
//        $validator = Validator::make(['uploaded_file' => $uploadedFile], ['uploaded_file' => 'image']);
//
////        fclose($tmpFile);
//
//        return ! $validator->fails();
//    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The image has none of the following extensions: ' . implode(', ',  $this->extensions->toArray()) . '.';
    }
}
