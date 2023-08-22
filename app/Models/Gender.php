<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $fillable =[
        'gender_id',
        'gender_name',
        
    ];

    protected $primaryKey='gender_id';
    protected $table = 'tbl_gender';
    public $timestamps = false;

}
