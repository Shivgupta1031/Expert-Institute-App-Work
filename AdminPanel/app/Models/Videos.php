<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videos extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    public $table = "youtube_videos";

    public $appends = ['course_title', 'category'];

    public function getCourseTitleAttribute()
    {
        return PaidCourses::where('id', $this->course_id)->value('title');
    }

    public function getCategoryAttribute()
    {
        return VideosCategory::where('id', $this->category_id)->value('category');
    }
}
