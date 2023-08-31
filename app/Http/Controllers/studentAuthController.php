<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\studentLogin;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;

use Illuminate\Notifications\Notifiable;


class studentAuthController extends Controller
{
    use HasApiTokens, Notifiable;

    public function login(Request $request)
    {
        
        $validator = $request->validate(['reg_number'=>'required|string', 'password'=>'required|string']);
        $regnumber = $validator['reg_number'];
        $password = $validator['password'];

        $students = studentLogin::join('tblstudent_passwords', 'tblstudent_passwords.regnum', '=', 'tblstudent.reg_number')
            ->where('tblstudent.reg_number', $regnumber)
            ->where('tblstudent_passwords.password', $password)
            ->first();

            if ($students) {
                $token = $students->createToken('Laravel Password Grant Client')->accessToken;
                return response()->json(
                    [
                        'token'=>$token,
                        'student_id'=>$students->student_id,
                        'first_name'=>$students->first_name,
                        'last_name'=>$students->surname,
                        'status'=>200,
                        'message'=>'User Logged In Successful'
                
                ]);
   
            } else {
                $response = ["message" =>'Username or password does not exist'];
                $response['status'] = 0;
                return response($response, 401);
            }
    }
    
}
