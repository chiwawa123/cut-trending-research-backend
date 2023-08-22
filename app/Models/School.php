<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable =[
        'school_id',
        'school_name',
        
    ];

    protected $primaryKey='school_id';
    protected $table = 'tbl_school';
    public $timestamps = false;

    public function TopicCategory(){
        return $this->belongsToMany(TopicCategory::class,'tbl_school_topic_category', 'school_id','topic_category_id');
    }
}
