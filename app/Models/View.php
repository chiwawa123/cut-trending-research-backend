<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;
    protected $fillable =[
        'view_id',
        'student_id',
        'topic_id',
        'is_viewed',

        
    ];

    protected $primaryKey='view_id';
    protected $table = 'tbl_views';
    public $timestamps = false;
}
