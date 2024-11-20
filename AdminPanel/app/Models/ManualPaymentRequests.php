<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualPaymentRequests extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    public $table = "manual_payment_requests";
    public $appends = ['username', 'phone_number', 'profile_pic'];

    public function getUsernameAttribute()
    {
        return Users::where('uid', $this->user_uid)->value('username');
    }

    public function getPhoneNumberAttribute()
    {
        return Users::where('uid', $this->user_uid)->value('phone_number');
    }

    public function getProfilePicAttribute()
    {
        return Users::where('uid', $this->user_uid)->value('profile_pic');
    }
}
