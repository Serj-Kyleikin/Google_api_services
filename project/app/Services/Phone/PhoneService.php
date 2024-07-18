<?php

namespace App\Services\Phone;

use App\Models\{
    User\Phone,
};

class PhoneService
{
    public function getRegisteredPhone(?string $phone): Phone|null
    {
        return $phone
            ? Phone::where('phone', $this->formatPhone($phone))->first()
            : null;
    }

    public function formatPhone(?string $phone): string|null
    {
        if($phone) {

            $phone = preg_replace('/[^0-9]/', '', $phone);

            if(strpos($phone, '8') === 0) {
                $phone = '7' . substr($phone, 1);
            }
        }

        return $phone;
    }

    public function isNotRegistered(string $phone): bool
    {
        return $this->getRegisteredPhone($phone) == null;
    }
}