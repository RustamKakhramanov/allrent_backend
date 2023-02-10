<?php

namespace App\Traits;

use App\Models\Contact;
use App\Enums\ContactEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

trait HasContacts
{
    use HasRelationships;

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function getContact(ContactEnum $type)
    {
        if ($contact = $this->contacts()->whereType($type->value)->first()) {
            return $contact->value;
        }
        return null;
    }

    public function setContact(ContactEnum $type, string $value, string $owner = null)
    {
        $data = [
            'type' => $type->value,
            'value' => $value
        ];

        if($owner){
            $data['owner'] = $owner;
        }

        return $this->contacts()->create($data);
    }

    public function setWhatsApp($value, string $owner = null)
    {
        return $this->setContact(ContactEnum::WhatsApp, $value, $owner);
    }

    public function setPhone($value, string $owner = null)
    {
        return $this->setContact(ContactEnum::Phone, $value, $owner);
    }

    public function setMail($value, string $owner = null)
    {
        return $this->setContact(ContactEnum::Mail, $value, $owner);
    }

    public function setTelegram($value, string $owner = null)
    {
        return $this->setContact(ContactEnum::Telegram, $value, $owner);
    }

    public function setInstagram($value, string $owner = null)
    {
        return $this->setContact(ContactEnum::Instagram, $value, $owner);
    }

  

    protected function whatsApp(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->setWhatsApp($value),
            get: fn () => $this->getContact(ContactEnum::WhatsApp),
        );
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->setPhone($value),
            get: fn () => $this->getContact(ContactEnum::Phone),
        );
    }

    protected function mail(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->setMail($value),
            get: fn () => $this->getContact(ContactEnum::Mail),
        );
    }
}
