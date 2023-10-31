<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable =[
        'reply_id',
        'student_id',
        'testimonial_id',
        'reply_description',
        
        
    ];

    protected $primaryKey='reply_id';
    protected $table = 'tbl_reply';
    public $timestamps = false;
}
