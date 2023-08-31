<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class studentLogin extends Authenticatable
{
    use HasApiTokens,Notifiable;

    protected $table='tblstudent';
    protected $guard = 'api-student';
    public $timestamps=false;
    protected $primaryKey='student_id';
    protected $fillable=[
        'student_id',
        'reg_number',
        'first_name',
        'last_name',
    ];


    



    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
