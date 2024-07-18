<?php

namespace App\Models\User;

use App\Models\{
    User,
};
use Illuminate\{
    Database\Eloquent\Model,
    Database\Eloquent\Relations\HasOne,
};

class Phone extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'phone',
        'code',
        'verified_at'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
