<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PDFNotes extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    public $table = "pdf_notes";

    public $appends = ['course_title'];

    public function getCourseTitleAttribute()
    {
        return PaidCourses::where('id', $this->course_id)->value('title');
    }

}
