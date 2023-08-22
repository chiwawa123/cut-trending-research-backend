<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gender;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GenderController extends Controller
{
    public function addGender(Request $request, Exception $exception){
      $validator = Validator::make($request->all(), [
        'gender_name'=> 'required|unique:tbl_gender'
    ]);
    if ($validator->fails())
    {
        $response['status']=0;
        $response['message']= $validator->errors()->toJson();
  
    }else{

    $gender = Gender::create([
        'gender_name'=>$request->gender_name,
    ]);


        $response['status']=200;
        $response['message']= 'Gender added successfully';

    }
    return response()->json($response);
    }
    public function viewGender(){

       $getGender = Gender::all();
       return response()->json($getGender, 200);
    }
    public function deleteGender(Request $request){
      $getGender = $request['gender_id'];

      $deleteGender = Gender::findorfail($getGender);


      if(!$deleteGender){
        $response['status']=404;
        $response['message']= 'Gender not found';
     
    }else{
        $deleteGender->delete();
        $response['status']=200;
        $response['message']= 'Gender was removed Successfully';
        

    }
    
    return response()->json($response);

  }
  public function updateGender(Request $request, Exception $exception)
  {   
    $request->validate([
      'gender_name' => 'required',
   
  ]);

      $getGender = $request['gender_id'];

      $gender = Gender::findorfail($getGender);

      $gender->gender_name = $request['gender_name'];

      if ($gender->save()) {
        return response()->json(['message' => 'Gender was updated successfully','status'=>200]);
    } else {
        return response()->json(['message' => $exception->getMessage()]);
    }
  }
}
