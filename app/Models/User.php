<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailNotification;
use App\Notifications\CustomResetPasswordNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
   

protected $fillable = ['name', 'email', 'password', 'phone', 'profile_picture', 'role', 'last_login_at'];


// inside the class User extends Authenticatable
public function tickets(){ return $this->hasMany(\App\Models\Ticket::class); }
public function events(){ return $this->hasMany(\App\Models\Event::class, 'created_by'); }
public function isAdmin(): bool { return $this->role === 'admin'; }
public function isManager(): bool { return $this->role === 'manager'; }
public function isOrganizer(): bool { return $this->role === 'organizer'; }
public function isAdminOrManager(): bool { return in_array($this->role, ['admin', 'manager']); }

// Notification relationships
public function notifications()
{
    return $this->hasMany(UserNotification::class, 'user_id')->orderBy('created_at', 'desc');
}

public function sentNotifications()
{
    return $this->hasMany(UserNotification::class, 'sender_id')->orderBy('created_at', 'desc');
}

public function unreadNotifications()
{
    return $this->hasMany(UserNotification::class, 'user_id')->where('is_read', false)->orderBy('created_at', 'desc');
}

public function getUnreadNotificationCountAttribute()
{
    return $this->unreadNotifications()->count();
}




    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
