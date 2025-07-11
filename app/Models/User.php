<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ✅ لو بتستخدم Sanctum

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

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
            // 'password' => 'hashed',
        ];
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function isDonor(): bool
    {
        return $this->role === 'donor';
    }

    public function isOrg(): bool
    {
        return $this->role === 'org';
    }

    public function isReceiver(): bool
    {
        return $this->role === 'receiver';
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function donationRequests()
    {
        return $this->hasMany(DonationRequest::class);
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
}
