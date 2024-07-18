<?php

namespace App\Models\User\Social;

use Illuminate\{
    Database\Eloquent\Model,
};

/**
 * @OA\Schema(
 *     schema="UserCalendarEvents",
 *     @OA\Property(property="id", type="integer", description="id", example=1),
 *     @OA\Property(property="calendar_type", type="string", description="Тип календаря", example="google"),
 *     @OA\Property(property="start_date", type="string", example="2024-07-18 11:00"),
 *     @OA\Property(property="end_date", type="string", example="2024-07-18 12:00"),
 *     @OA\Property(property="calendar_link", type="string", example="https://www.google.com/calendar/event?eid=YWtxMXB0ZGl"),
 *     @OA\Property(property="meet_link", type="string", example="https://meet.google.com/bbm-qhcz-zdf"),
 *     @OA\Property(property="creater_id", type="integer", example=1),
 *     @OA\Property(property="client_id", type="integer", example=2)
 *  )
 * 
 */
class UserCalendarEvents extends Model
{
    protected $table = 'user_calendar_events';

    public $timestamps = false;
    protected $guarded = ['id'];

    protected $fillable = [
        'calendar_type',
        'start_date',
        'end_date',
        'calendar_link',
        'meet_link',
        'creater_id',
        'client_id'
    ];
}
