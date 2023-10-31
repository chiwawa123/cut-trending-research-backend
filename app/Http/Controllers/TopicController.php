<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Topic;
use App\Models\View;
use App\Models\TopicCategory;
use App\Models\TopicCategoryTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    public function viewTopics(Request $request)
    {
        $topic = DB::select("SELECT
        tbl_topic_category.topic_category_name,
        tbl_topic.topic_name,
        tbl_topic.first_name,
        tbl_topic.last_name,
        tbl_topic.topic_id,
        tbl_topic.topic_category_id,
        tbl_topic.description,
        tbl_topic.is_active,
        tbl_topic.date_posted 
    FROM
        tbl_topic
        INNER JOIN tbl_topic_category ON tbl_topic_category.topic_category_id = tbl_topic.topic_category_id 
    ORDER BY
        tbl_topic.date_posted DESC");

        return response()->json($topic, 200);
    }
    public function deleteTopic(Request $request)
    {

        $deleteTopic = $request['topic_id'];

        $topic = Topic::findorfail($deleteTopic);
        if (!$topic) {
            $response['status'] = 404;
            $response['message'] = 'topic not found';
        } else {
            $topic->delete();
            $response['status'] = 200;
            $response['message'] = 'Topic was removed Successfully';
        }
        return response()->json($response);
    }
    public function addTopic(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'topic_name' => 'required|unique:tbl_topic',
            'topic_category_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'description' => 'required',
            'date_posted'=>'required|date|before_or_equal:now',
            'is_active' => 'required',
            'image' => 'required',
        ]);
        if ($validator->fails()) {
            $response['status'] = 0;
            $response['message'] = $validator->errors()->toJson();
        } else {

            $topic = new Topic();
            $topic->topic_name = $request['topic_name'];
            $topic->description = $request['description'];
            $topic->first_name = $request['first_name'];
            $topic->last_name = $request['last_name'];
            $topic->date_posted = $request['date_posted'];
            $topic->topic_category_id = $request['topic_category_id'];
            $topic->is_active = $request['is_active'];

            $fileName = time() . '.' . $request->image->extension();
            $request->image->move('uploads/images', $fileName);
            $topic->image = $fileName;
            $topic->save();

            $response['status'] = 200;
            $response['message'] = 'Topic was added successfully';
        }
        return response()->json($response);
    }


    public function updateTopic(Request $request)
    {
        $request->validate([
            'topic_name' => 'required',
            'description' => 'required',
            'image' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'date_posted'=>'required|date|before_or_equal:now',
            'topic_category_id' => 'required',
        ]);

        $topic_id = $request['topic_id'];
        $getTopic = Topic::findorfail($topic_id);


        $fileName = '';

        if ($request->hasFile('image')) {
            $fileName = time() . '.' . $request->image->extension();
            $request->image->move('uploads/images', $fileName);

            if ($getTopic->image) {
                Storage::delete('uploads/images' . $getTopic->image);
            }
        } else {
            $fileName = $getTopic->image;
        }

        $getTopic->description = $request['description'];
        $getTopic->topic_name = $request['topic_name'];
        $getTopic->first_name = $request['first_name'];
        $getTopic->last_name = $request['last_name'];
        $getTopic->date_posted = $request['date_posted'];
        $getTopic->topic_category_id = $request['topic_category_id'];
        $getTopic->is_active = $request['is_active'];
        $getTopic->image = $fileName;



        if ($getTopic->save()) {
            return response()->json(['message' => 'Topic  was updated successfully', 'status' => 200]);
        } else {
            return response()->json(['message' => 'Ooops Something went wrong'], 200);
        }
    }

    public function topicSlider()
    {

        $getTopicSlider = DB::select("SELECT
        tbl_topic.topic_id,
        tbl_topic.topic_name,
        tbl_topic.topic_category_id,
        tbl_topic.description,
        tbl_topic.image,
        tbl_topic.is_active,
        tbl_topic.date_posted,
        tbl_topic_category.topic_category_name 
    FROM
        tbl_topic
        INNER JOIN tbl_topic_category ON tbl_topic.topic_category_id = tbl_topic_category.topic_category_id 
    ORDER BY
        tbl_topic.date_posted DESC");


        return response()->json(

            $getTopicSlider,
            200
        );
    }

    public function topicTestimonial(Request $request){

        $topic_id = $request['topic_id'];
        $student_id = $request['student_id'];
        $viewTopic = View::join('tblstudent', 'tblstudent.student_id', '=', 'tbl_views.student_id')
            ->where('tblstudent.student_id', $student_id)
            ->where('tbl_views.topic_id',  $topic_id)
            ->first();

        if (!$viewTopic) {
            $view =  new View();
            $view->student_id = $request['student_id'];
            $view->topic_id=$request['topic_id'];
            $view->is_viewed=$request['is_viewed'];

            $view->save();
        }   
    
        $topicTest = DB::select("SELECT
        backend.tbl_topic.topic_id,
        backend.tbl_topic.topic_name,
        backend.tbl_topic.first_name,
        backend.tbl_topic.last_name,
        backend.tbl_topic.views,
        backend.tbl_topic.topic_category_id,
        backend.tbl_topic.description,
        backend.tbl_topic.image,
        backend.tbl_topic.is_active,
        backend.tbl_topic.date_posted,
        backend.tbl_topic_category.topic_category_name 
    FROM
        backend.tbl_topic
        INNER JOIN backend.tbl_topic_category ON backend.tbl_topic.topic_category_id = backend.tbl_topic_category.topic_category_id 
    WHERE
        backend.tbl_topic.topic_id = $topic_id");

        $query = DB::select("SELECT
        backend.tbl_testimonial.testimonial_id,
        backend.tbl_testimonial.description,
        backend.tbl_testimonial.topic_id,
        backend.tbl_testimonial.date_posted,
        backend.tbl_testimonial.is_active,
        backend.tblstudent.student_id,
        backend.tblstudent.reg_number,
        backend.tblstudent.first_name,
        backend.tblstudent.surname 
    FROM
        backend.tbl_testimonial
        INNER JOIN backend.tblstudent ON backend.tbl_testimonial.student_id = backend.tblstudent.student_id 
    WHERE
        backend.tbl_testimonial.topic_id = $topic_id
        AND backend.tbl_testimonial.is_active = 'active' 
    ORDER BY
        backend.tbl_testimonial.date_posted DESC");

        return response()->json(['testimonials'=>$query,'topicTestimonial'=>$topicTest]);
      }
      public function getTestimonialsPerTopic( Request $request){
        $topic_id = $request['topic_id'];
        $testimonials = DB::select("SELECT
        backend.tbl_testimonial.testimonial_id,
        backend.tbl_testimonial.description,
        backend.tbl_testimonial.topic_id,
        backend.tbl_testimonial.date_posted,
        backend.tbl_testimonial.is_active,
        backend.tblstudent.student_id,
        backend.tblstudent.reg_number,
        backend.tblstudent.first_name,
        backend.tblstudent.surname 
    FROM
        backend.tbl_testimonial
        INNER JOIN backend.tblstudent ON backend.tbl_testimonial.student_id = backend.tblstudent.student_id 
    WHERE
        backend.tbl_testimonial.topic_id = $topic_id
        AND backend.tbl_testimonial.is_active = 'active' 
    ORDER BY
        backend.tbl_testimonial.date_posted DESC");

        return response()->json($testimonials);

      }

      public function topicPivot(){

       $getSchools = School::all();
       $topicCategotry = TopicCategory::all();

        return response()->json(['getSchools'=>$getSchools,'topicCategory'=>$topicCategotry]);
      }
      public function addTopicCategorySchool(Request $request){
        $validator = Validator::make($request->all(), [
          'school_id'=> 'required',
          'topic_category_id'=> 'required',

      ]);
      if ($validator->fails())
      {
          $response['status']=0;
          $response['message']= $validator->errors()->toJson();
    
      }else{
  
      TopicCategoryTopic::create([
          'school_id'=>$request->school_id,
          'topic_category_id'=>$request->topic_category_id,

      ]);
  
  
          $response['status']=200;
          $response['message']= 'Topic Category assigned successfully';
  
      }
      return response()->json($response);

    }

    public function topicsInCategory(Request $request)
{
    $topicCategory_id = $request['topic_category_id'];
    $topic_id = $request['topic_id'];

   $getTopicsInCategory = DB::select("SELECT
   backend.tbl_topic.topic_id,
   backend.tbl_topic.topic_name,
   backend.tbl_topic.topic_category_id,
   backend.tbl_topic.description,
   backend.tbl_topic.image,
   backend.tbl_topic.is_active,
   backend.tbl_topic.date_posted,
   backend.tbl_topic_category.topic_category_name 
FROM
   backend.tbl_topic
   INNER JOIN backend.tbl_topic_category ON backend.tbl_topic.topic_category_id = backend.tbl_topic_category.topic_category_id 
WHERE
   backend.tbl_topic.topic_category_id = $topicCategory_id  
   AND backend.tbl_topic.topic_id <> $topic_id");

    return response()->json($getTopicsInCategory);
}

public function searchTopic(Request $request){
   
    
    $data = $request->input('search_topic');

        
        $topic = Topic::Select('tbl_topic.topic_name','tbl_topic.description','tbl_topic.date_posted')
        ->whereRaw("topic_name LIKE '%" .$data.  "%'")
        ->orwhereRaw("date_posted LIKE '%" .$data.  "%'");
   
    return response()->json($topic->get());
}

public function getEstimateReadingTime(Request $request) {
 
    $wpm = 200;
    $topic_id = $request['topic_id'];

    $topic = DB::select("SELECT
	backend.tbl_topic.topic_id,
	backend.tbl_topic.topic_name,
	backend.tbl_topic.topic_category_id,
	backend.tbl_topic.description,
	backend.tbl_topic.image,
	backend.tbl_topic.is_active,
	backend.tbl_topic.date_posted,
	backend.tbl_topic.views 
FROM
	backend.tbl_topic 
WHERE
	backend.tbl_topic.topic_id = $topic_id");

    // var_dump($topic);
    // die();

     $wordCount = str_word_count(strip_tags($topic[0]->description));

    $minutes = (int) floor($wordCount / $wpm);
    $seconds = (int) floor($wordCount % $wpm / ($wpm / 60));

    if ($minutes === 0) {
        return response()->json(["time"=>"{$seconds} seconds read"]);
    } else {
        return response()->json(["time"=>"{$minutes} minutes read"]);
    }     

}




}
