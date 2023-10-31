<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reply;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
class TestimonialController extends Controller
{
  public function viewTestimonials()
  {
    $testimonials = DB::select("SELECT
        tblstudent.student_id,
        tblstudent.reg_number,
        tblstudent.first_name,
        tblstudent.surname,
        tbl_testimonial.testimonial_id,
        tbl_testimonial.description,
        tbl_testimonial.date_posted,
        tbl_testimonial.is_active,
        tbl_topic.topic_name 
      FROM
        tblstudent
        INNER JOIN tbl_testimonial ON tblstudent.student_id = tbl_testimonial.student_id
        INNER JOIN tbl_topic ON tbl_testimonial.topic_id = tbl_topic.topic_id 
      WHERE
        tbl_testimonial.is_active = 'inactive'");

    return response()->json($testimonials, 200);
  }


  public function addTestimonial(Request $request)
  {
    $date = Carbon::now()->format('Y-m-d');
    $validator = Validator::make($request->all(), [
      'student_id' => 'required',
      'topic_id' => 'required',
      'description' => 'required',
      'is_active' => 'required',
    ]);
    if ($validator->fails()) {
      $response['status'] = 0;
      $response['message'] = $validator->errors()->toJson();
    } else {
      $testimonial = new Testimonial();
      $testimonial->student_id = $request['student_id'];
      $testimonial->description = $request['description'];
      $testimonial->date_posted = $date;
      $testimonial->topic_id = $request['topic_id'];
      $testimonial->is_active = $request['is_active'];
      $testimonial->save();

      $response['status'] = 200;
      $response['message'] = 'Testimonial was added successfully';
    }
    return response()->json($response);
  }
  public function deleteTestimonial(Request $request)
  {
    $getTestimonial_id = $request['testimonial_id'];

    $deleteTestimonial = Testimonial::findorfail($getTestimonial_id);
    if (!$deleteTestimonial) {
      $response['status'] = 404;
      $response['message'] = 'Testimonial not found';
    } else {
      $deleteTestimonial->delete();
      $response['status'] = 200;
      $response['message'] = 'Testimonial was removed Successfully';
    }


    return response()->json($response);
  }
  public function updateTestimonial(Request $request)
  {

    $testimonial_id = $request['testimonial_id'];
    $getTestimonial = Testimonial::findorfail($testimonial_id);

    $getTestimonial->is_active = $request['is_active'];

    if ($getTestimonial->save()) {
      return response()->json(['message' => 'Testimonial  was activated successfully', 'status' => 200]);
    } else {
      return response()->json(['message' => 'Ooops Something went wrong'], 200);
    }
  }

  public function testimonialCount(Request $request)
  {

    $topic_id = $request['topic_id'];

    $count = DB::select("SELECT
    tbl_topic.topic_id,
    tbl_topic.topic_name,
    tbl_testimonial.testimonial_id,
    tbl_testimonial.student_id,
    tbl_testimonial.description 
  FROM
    tbl_testimonial
    INNER JOIN tbl_topic ON tbl_testimonial.topic_id = tbl_topic.topic_id 
  WHERE
    tbl_testimonial.topic_id = $topic_id ");

    return response()->json($count);
  }
  public function replyTestimonial(Request $request)
  {

    $testimonial_id = $request['testimonial_id'];

    $postReply = DB::select("SELECT
    tbl_testimonial.testimonial_id,
    tbl_testimonial.student_id,
    tbl_testimonial.description,
    tbl_testimonial.topic_id,
    tbl_testimonial.date_posted,
    tbl_topic.topic_name,
    tbl_topic.topic_category_id,
    tblstudent.first_name,
    tblstudent.surname,
    tblstudent.reg_number 
FROM
    tbl_testimonial
    INNER JOIN tblstudent ON tbl_testimonial.student_id = tblstudent.student_id
    INNER JOIN tbl_topic ON tbl_testimonial.topic_id = tbl_topic.topic_id 
WHERE
    tbl_testimonial.testimonial_id = $testimonial_id");

    if ($postReply) {
      $reply = new Reply();
      $reply->student_id = $request->student_id;
      $reply->testimonial_id = $testimonial_id;
      $reply->reply_description = $request->reply_description;
      $reply->save();
    } else {
      return response()->json(['message' => 'empty']);
    }

    return response()->json(['message' => 'reply sent successfully', 'status' => 200]);
  }

  public function viewTestimonialReply(Request $request)
  {

    $testimonial_id = $request['testimonial_id'];
    $getReply = DB::select("SELECT
	tbl_reply.reply_id, 
	tbl_reply.reply_description, 
	tbl_testimonial.description, 
	tbl_reply.student_id, 
	tbl_reply.testimonial_id, 
	tbl_testimonial.is_active, 
	tbl_testimonial.date_posted
FROM
	tbl_reply
	INNER JOIN
	tbl_testimonial
	ON 
		tbl_reply.testimonial_id = tbl_testimonial.testimonial_id
WHERE
	tbl_reply.testimonial_id = $testimonial_id");

    return response()->json($getReply, 200);
  }

  public function testimonialTopicCount(Request $request)
  {
    $topic_id = $request['topic_id'];
    $count = DB::select("SELECT
    COUNT( tbl_testimonial.topic_id ) AS count_testimonial 
  FROM
    tbl_topic
    INNER JOIN tbl_testimonial ON tbl_topic.topic_id = tbl_testimonial.topic_id 
  WHERE
    tbl_testimonial.topic_id = $topic_id
    AND tbl_testimonial.is_active = 'active'");
    return response()->json($count);
  }
}
