<?php

namespace App\Services\Social\DTOs;

class UserInfoDTO
{
    private string   $providerUserId;
    private string   $name;
    private string   $email;
    private ?string  $sex = null;
    private ?string  $birthdate = null;
    private ?string  $phone = null;

    public function __construct()
    {
    }

    public function setProviderUserId(string $providerUserId): void
    {
        $this->providerUserId = $providerUserId;
    }

    public function getProviderUserId(): string
    {
        return $this->providerUserId;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setEmail(string $email): void
    {
        $this->email = strtolower($email);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setSex(?string $sex): void
    {
        $this->sex = $sex;
    }

    public function getSex(): string|null
    {
        return $this->sex;
    }

    public function setBirthdate(?string $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function getBirthdate(): string|null
    {
        return $this->birthdate;
    }

    public function setPhone(?string $phone): void
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if(strpos($phone, '8') === 0) {
            $phone = '7' . substr($phone, 1);
        }

        $this->phone = $phone;
    }

    public function getPhone(): string|null
    {
        return $this->phone;
    }
}
