<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Traits\User\UserGetters;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens, UserGetters;

    protected $with = ['roles'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $appends = ['image'];

    /*
     * Social Auth Relationship
     */
    public function social()
    {
        return $this->hasMany(UserSocial::class);
    }

    public function hasSocialLinked($service)
    {
        return (bool) $this->social->where('service', $service)->count();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

        'password', 'remember_token', 'confirmation_code',

    ];

    public function getstatus()
    {
        return $this->status == 0 ?  trans('global.sts.deactivated') : trans('global.sts.activated');
    }


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($this, $token));
    }

    public function hasAccess($permissions)
    {
        if (is_array($permissions)) {
            $permissions_array = array_push($permissions, 'view_admin_area');
        } else {
            $permissions_array = [$permissions, 'view_admin_area'];
        }
        return $this->can($permissions_array, true);
    }

    public function routeNotificationForSlack(): string
    {
        return config('env.SLACK_WEBHOOK_URL');
    }
    
    public function routeNotificationForWhatsApp(): string
    {
        return $this->phone;
    }

    public function routeNotificationForSms(): string
    {
        return $this->phone;
    }

    public function routeNotificationForBrowser(): string
    {
        return config('notify.browser.to');
    }

    public function routeNotificationForMobile(): string
    {
        return config('notify.pushed.to');
    }
}
