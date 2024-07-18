<?php

namespace App\Services\Google;

use App\Models\{
    User,
    User\SocialAccount,
    User\Social\UserCalendarEvents,
};
use App\Services\Google\DTOs\EventDTO;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Exception;
use Illuminate\{
    Http\Response,
};

class GoogleCalendarService extends GoogleService
{
    private int     $socialId;
    private string  $startDate;
    private string  $endDate;
    private string  $text;
    private bool    $isConference;
    private string  $calendarId;

    private Google_Client           $googleClient;
    private Google_Service_Calendar $calendarService;
    private                         $googleCalendarEvent;

    public function createEvent(EventDTO $eventDTO): UserCalendarEvents
    {
        $this->setDTO($eventDTO);
        $this->setCalendarId();

        $configJson = base_path() . '/config/Google/Calendar.json';
        throw_unless(
            file_exists($configJson),
            Exception::class,
            "Your google calendar config is missing. Create and load it from https://console.cloud.google.com/apis/api/calendar-json.googleapis.com/credentials?your_project=project_name",
            Response::HTTP_NOT_FOUND
        );
        $applicationName = 'Google API';

        $this->googleClient = new Google_Client();

        $this->googleClient->setApplicationName($applicationName);
        $this->googleClient->setAuthConfig($configJson);
        $this->googleClient->setAccessType("offline");
        $this->googleClient->addScope([
            \Google_Service_Calendar::CALENDAR,
            \Google_Service_Gmail::GMAIL_SEND,
            \Google_Service_Calendar::CALENDAR_EVENTS,
        ]);

        $this->createMeetingInCalendar();
        $userEvent = $this->createUserCalendarEvent();

        return $userEvent;
    }

    private function setDTO(EventDTO $eventDTO)
    {
        $this->socialId = $eventDTO->getSocialId();
        $this->startDate = $eventDTO->getStartDate();
        $this->endDate = $eventDTO->getEndDate();
        $this->text = $eventDTO->getText();
        $this->isConference = $eventDTO->getIsConference();
    }

    private function setCalendarId(): void
    {
        $socialAccount = SocialAccount::find($this->socialId);

        throw_unless(
            $socialAccount,
            Exception::class,
            "Social account not found",
            Response::HTTP_NOT_FOUND
        );
        throw_if(
            $socialAccount->provider != 'google',
            Exception::class,
            "This social account cant' be used for google api",
            Response::HTTP_FORBIDDEN
        );

        throw_unless(
            $socialAccount->userEmail->email,
            Exception::class,
            "User don't have  email",
            Response::HTTP_FORBIDDEN
        );

        $this->calendarId = $socialAccount->userEmail->email;
    }

    private function createMeetingInCalendar(): void
    {
        $start = new \DateTime($this->startDate);
        $end = new \DateTime($this->endDate);

        $this->calendarService = new Google_Service_Calendar($this->googleClient);

        $calendarEvent = new Google_Service_Calendar_Event([
            'summary' => $this->text,
            'start' => [
                'dateTime' => $start->format(\DateTime::RFC3339),
                'timeZone' => 'Europe/Moscow',
            ],
            'end' => [
                'dateTime' => $end->format(\DateTime::RFC3339),
                'timeZone' => 'Europe/Moscow',
            ],
        ]);

        $organizer = new \Google_Service_Calendar_EventOrganizer();
        $organizer->setEmail($this->calendarId);
        $organizer->setDisplayName($this->calendarId);

        $calendarEvent->setOrganizer($organizer);

        $this->googleCalendarEvent = $this->calendarService->events->insert($this->calendarId, $calendarEvent);
        $this->isConference && $this->createConference();
    }

    private function createConference(): void
    {
        $conferenceSolutionKey = new \Google_Service_Calendar_ConferenceSolutionKey();
        $conferenceSolutionKey->setType("hangoutsMeet");

        $conferenceRequest = new \Google_Service_Calendar_CreateConferenceRequest();
        $conferenceRequest->setRequestId(uniqid());
        $conferenceRequest->setConferenceSolutionKey($conferenceSolutionKey);

        $conferenceSolution = new \Google_Service_Calendar_ConferenceSolution();
        $conferenceSolution->setKey($conferenceSolutionKey);

        $conference = new \Google_Service_Calendar_ConferenceData();
        $conference->setCreateRequest($conferenceRequest);
        $conference->setConferenceSolution($conferenceSolution);

        $this->googleCalendarEvent->setConferenceData($conference);

        $event = $this->calendarService->events->patch($this->calendarId, $this->googleCalendarEvent->id, $this->googleCalendarEvent, ['conferenceDataVersion' => 1]);

        $this->googleCalendarEvent = $event;
    }

    private function createUserCalendarEvent(): UserCalendarEvents
    {
        $creater = SocialAccount::where('user_id', auth()->id())->first();

        $event = UserCalendarEvents::create([
            'calendar_type' => 'google',
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'calendar_link' => $this->googleCalendarEvent->htmlLink,
            'creater_id' => $creater->id,
            'client_id' => $this->socialId
        ]);

        return $event ;
    }
}
