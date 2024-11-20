<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebooks extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    public $table = "ebooks";

    public $appends = ['is_purchased'];

    public static $uid = '';

    public function getIsPurchasedAttribute()
    {
        if (strlen($this::$uid) > 0) {
            return UserOrders::where('user_uid', $this::$uid)->where('type', 1)->where('details', $this->id)->exists();
        } else {
            return false;
        }
    }
}
