<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockTests extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    public $table = "mock_tests";

    public $appends = ['category','questions'];

    public function getCategoryAttribute()
    {
        return MockTestCategory::where('id', $this->test_category_id)->value('title');
    }

    public function getQuestionsAttribute()
    {
        return TestQuestions::where('mock_test_id', $this->id)->count();
    }
}
