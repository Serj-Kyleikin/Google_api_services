<?php

namespace App\Services\Google\DTOs;

class EventDTO
{
    private int      $socialId;
    private string   $startDate;
    private string   $endDate;
    private string   $text;
    private bool     $isConference;

    public function __construct()
    {
    }

    public function setSocialId(string $socialId): void
    {
        $this->socialId = $socialId;
    }

    public function getSocialId(): int
    {
        return $this->socialId;
    }

    public function setStartDate(string $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function setEndDate(string $endDate): void
    {
        $this->endDate = strtolower($endDate);
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setIsConference(bool $isConference): void
    {
        $this->isConference = $isConference;
    }

    public function getIsConference(): bool
    {
        return $this->isConference;
    }
}
