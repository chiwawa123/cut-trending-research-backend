<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicCategory extends Model
{
    protected $fillable =[
        'topic_category_id',
        'topic_category_name',
        'school_id',
        
    ];

    protected $primaryKey='topic_category_id';
    protected $table = 'tbl_topic_category';
    public $timestamps = false;

    public function School(){
        return $this->belongsToMany(School::class,'tbl_school_topic_category', 'topic_category_id','school_id');
    }
}
