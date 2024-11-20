<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockTestCategory extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    public $table = "mock_test_category";

    public $appends = ['is_purchased'];

    public static $uid = '';

    public function getIsPurchasedAttribute()
    {
        if (strlen($this::$uid) > 0) {
            return UserOrders::where('user_uid', $this::$uid)->where('type', 2)->where('details', $this->id)->exists();
        } else {
            return false;
        }
    }
}
