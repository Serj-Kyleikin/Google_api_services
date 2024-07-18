<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\{
    Database\Eloquent\Model,
    Database\Eloquent\Relations\HasOne,
};

/**
 * @OA\Schema(
 *     schema="SocialAccount",
 *     @OA\Property(property="id", type="integer", description="id", example=5),
 *     @OA\Property(property="user_id", type="integer", description="user id", example=2),
 *     @OA\Property(property="provider", type="string", example="google"),
 *     @OA\Property(property="provider_user_id", type="string", example="112060603478612537035"),
 *     @OA\Property(property="created_at", type="string", example="2024-07-18T11:14:46.000000Z"),
 *     @OA\Property(property="updated_at", type="string", example="2024-07-18T11:14:46.000000Z"),
 *  )
 */
class SocialAccount extends Model
{
    protected $table = 'social_accounts';
    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
    ];

    protected $with = ['userEmail:id,email'];

    public function userEmail(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
