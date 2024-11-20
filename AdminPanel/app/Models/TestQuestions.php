<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestQuestions extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    public $table = "test_questions";

    public $appends = ['mock_test'];

    public function getMockTestAttribute()
    {
        return MockTests::where('id', $this->mock_test_id)->value('title');
    }
}
