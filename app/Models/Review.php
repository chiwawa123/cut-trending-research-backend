<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable =[
        'review_id',
        'dis_liked',
        'is_liked',
        'topic_id',
        
    ];

    protected $primaryKey='review_id';
    protected $table = 'tbl_reviews';
    public $timestamps = false;

}
