<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['hex', 'email', 'email_verified_at', 'password', 'google_id', 'username', 'display_name', 'name', 'bio', 'avatar', 'country_code', 'state_code'])]
#[Hidden(['password', 'remember_token'])]

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
        ];
    }

    
    // -----------------------------------------------------
    // ROUTE KEY
    // -----------------------------------------------------


    // Set route key

    public function getRouteKeyName()
    {
        return 'hex';   
    }


    // Retrieve route key value

    public function routeKeyValue()
    {
        $routeKeyValue = $this->getRouteKeyName();
        return $this->$routeKeyValue;
    }



    // Accessors

    public function getAvatarUrlAttribute(): string
    {
        if (! $this->avatar) {
            return asset('images/default-avatar-dark.png');
        }

        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }

        return Storage::url($this->avatar);
    }

    public function getCountryNameAttribute(): string
    {
        return config('countries')[$this->country_code] ?? 'Unknown';
    }

    public function getStateNameAttribute(): string
    {
        return config('states')[$this->state_code] ?? 'Unknown';
    }





    // Relationships
    
    public function transcriptions()
    {
        return $this->hasMany(Transcription::class);
    }
}
