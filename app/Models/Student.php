<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable =[
        'student_id',
        'first_name',
        'last_name',
        'gender_id',
        'image'
    ];

    protected $primaryKey='student_id';
    protected $table = 'tbl_student';
    public $timestamps = false;
}
