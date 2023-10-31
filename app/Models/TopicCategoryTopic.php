<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicCategoryTopic extends Model
{
    use HasFactory;
    protected $fillable =[
        'school_id',
        'topic_category_id',
        
    ];

    protected $table = 'tbl_school_topiccategory';
    public $timestamps = false;
}
