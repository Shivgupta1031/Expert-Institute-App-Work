<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrders extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    public $table = "user_orders";

    public $appends = ['username', 'phone_number', 'profile_pic', 'description','coupan'];

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

    public function getDescriptionAttribute()
    {
        if ($this->type == 0) {
            return PaidCourses::where('id', $this->details)->value('title');
        } else if ($this->type == 1) {
            return Ebooks::where('id', $this->details)->value('title');
        } else if ($this->type == 2) {
            return MockTestCategory::where('id', $this->details)->value('title');
        } else {
            return "";
        }
    }

    public function getCoupanAttribute()
    {
        return Coupans::where('id', $this->coupan_used)->value('code');
    }
}
