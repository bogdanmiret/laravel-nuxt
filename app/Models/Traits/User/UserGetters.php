<?php

namespace App\Models\Traits\User;

use App\Models\Attribute;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

trait UserGetters
{
    public function scopeOfNickname($query, $nickname)
    {
        return $query->where('name', $nickname);
    }

    /**
     * Пользователь по почте
     */
    public function scopeOfEmail($query, $email)
    {
        return $query->where('email', $email);
    }
}
