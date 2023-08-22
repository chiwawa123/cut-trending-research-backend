<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable =[
        'topic_id',
        'topic_name',
        'topic_category_id',
        'image',
        'description',
        'is_valid',
        'slider',
        'position',
        'date_posted',

      
        
    ];

    protected $primaryKey='topic_id';
    protected $table = 'tbl_topic';
    public $timestamps = false;
}
