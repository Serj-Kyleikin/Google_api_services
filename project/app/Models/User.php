<?php

namespace App\Models;

use App\Models\{
    User\Phone,
};
use App\Models\User\SocialAccount;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\{
    Database\Eloquent\Factories\HasFactory,
    Foundation\Auth\User as Authenticatable,
    Notifications\Notifiable,
    Database\Eloquent\Relations\HasOne,
};

/**
 * @OA\Schema(
 *     schema="UserEmail",
 *     @OA\Property(property="id", type="integer", description="id", example=1),
 *     @OA\Property(property="email", type="string", description="email id", example="some@mail.site"),
 *  )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'sex',
        'birthdate'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function phone(): HasOne
    {
        return $this->hasOne(Phone::class, 'user_id', 'id');
    }

    public function social(): HasOne
    {
        return $this->hasOne(SocialAccount::class, 'user_id', 'id');
    }
}
