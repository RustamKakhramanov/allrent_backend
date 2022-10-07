<?php

namespace App\Services\Auth;




use App\Models\User;
use Illuminate\Support\Str;

class GetGuestService
{
    public const GUEST_EMAIL_DOMAIN = 'appocore.io';

    private string $guestEmailDomain;

    public function __construct()
    {
        $this->guestEmailDomain = self::GUEST_EMAIL_DOMAIN;
    }

    public function __invoke(?string $tagName = null) : User
    {
        $guest = User::create(['password' => ($password = Str::random(255))]);

        $guest->update([
            'email' => ($email = "{$guest->id}@{$this->guestEmailDomain}"),
            'password' => $guest->guest_password
        ]);

        // $tag = empty($tagName) ? null : Tag::where('name', $tagName)->first();

        // if (! empty($tag)) {
        //     $tag->assignTo($guest);
        // }

        return $guest;
    }
}
