<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Users extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guarded = [];
    public $timestamps = false;
    public $table = "users";
    public $appends = ['password_decrypted'];

    protected $hidden = [
        'my-app-token',
    ];

    public function getPasswordDecryptedAttribute()
    {
        return base64_decode(base64_decode($this->password));
    }
}
