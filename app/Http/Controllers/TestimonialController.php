<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    public function viewTestimonials()
    {
        $testimonials = DB::select("SELECT
        tbl_testimonial.student_id,
        tbl_testimonial.testimonial_id,
        tbl_testimonial.description,
        tbl_testimonial.topic_id,
        tbl_testimonial.date_posted,
        tbl_student.first_name,
        tbl_student.last_name,
        tbl_topic.topic_name 
    FROM
        tbl_student
        INNER JOIN tbl_testimonial ON tbl_student.student_id = tbl_testimonial.student_id
        INNER JOIN tbl_topic ON tbl_testimonial.topic_id = tbl_topic.topic_id");

        return response()->json($testimonials,200);
    }


    public function addTestimonial(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'topic_id' => 'required',
            'date_posted'=>'required|date|before_or_equal:now',
            'description' => 'required',
        ]);
        if ($validator->fails())
        {
            $response['status']=0;
            $response['message']= $validator->errors()->toJson();
      
        }else{
    
        $testimonial = new Testimonial();
        $testimonial->student_id= $request['student_id'];
        $testimonial->description=$request['description'];
        $testimonial->date_posted=$request['date_posted'];
        $testimonial->topic_id=$request['topic_id'];
        $testimonial->save();

        $response['status']=200;
        $response['message']= 'Testimonial was added successfully';  
        }
        return response()->json($response);
      
   
    }
    public function deleteTestimonial(Request $request){
      $getTestimonial_id = $request['testimonial_id'];

      $deleteTestimonial = Testimonial::findorfail($getTestimonial_id);
      if(!$deleteTestimonial){
        $response['status']=404;
        $response['message']= 'Testimonial not found';
     
    }else{
        $deleteTestimonial->delete();
        $response['status']=200;
        $response['message']= 'Testimonial was removed Successfully';
        

    }

    
    return response()->json($response);

  }
  public function updateTestimonial(Request $request)
  {   
    $request->validate([
      'student_id' => 'required',
      'topic_id' => 'required',
      'date_posted'=>'required|date|before_or_equal:now',
      'description' => 'required',
  ]);

      $testimonial_id = $request['testimonial_id'];
      $getTestimonial = Testimonial::findorfail($testimonial_id);


      $getTestimonial->description = $request['description'];
      $getTestimonial->date_posted = $request['date_posted'];
      $getTestimonial->student_id = $request['student_id'];
      $getTestimonial->topic_id = $request['topic_id'];

      if($getTestimonial->save()){
        return response()->json(['message'=>'Testimonial  was updated successfully','status'=>200]);
      }else{
        return response()->json(['message'=>'Ooops Something went wrong'],200);
      }
  }
    
    
}
