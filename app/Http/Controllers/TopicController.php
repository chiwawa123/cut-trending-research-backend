<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Topic;
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
        tbl_topic.topic_id, 
        tbl_topic.topic_category_id, 
        tbl_topic.description, 
        tbl_topic.is_active, 
        tbl_topic.slider, 
        tbl_topic.date_posted
    FROM
    tbl_topic
       
        INNER JOIN
        tbl_topic_category
        ON 
            tbl_topic_category.topic_category_id = tbl_topic.topic_category_id");

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
            'description' => 'required',
            'date_posted' => 'required',
            'is_active' => 'required',
            'slider' => 'required',
            'position' => 'required',
            'image' => 'required',
        ]);
        if ($validator->fails()) {
            $response['status'] = 0;
            $response['message'] = $validator->errors()->toJson();
        } else {

            $topic = new Topic();
            $topic->topic_name = $request['topic_name'];
            $topic->description = $request['description'];
            $topic->date_posted = $request['date_posted'];
            $topic->topic_category_id = $request['topic_category_id'];
            $topic->position = $request['position'];
            $topic->is_active = $request['is_active'];
            $topic->slider = $request['slider'];

            $fileName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/images', $fileName);
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
            'date_posted' => 'required|date|before_or_equal:now',
            'topic_category_id' => 'required',
            'position' => 'required',
        ]);

        $topic_id = $request['topic_id'];
        $getTopic = Topic::findorfail($topic_id);


        $fileName = '';

        if ($request->hasFile('image')) {
            $fileName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/images', $fileName);

            if ($getTopic->image) {
                Storage::delete('public/images/' . $getTopic->image);
            }
        } else {
            $fileName = $getTopic->image;
        }

        $getTopic->description = $request['description'];
        $getTopic->topic_name = $request['topic_name'];
        $getTopic->date_posted = $request['date_posted'];
        $getTopic->topic_category_id = $request['topic_category_id'];
        $getTopic->position = $request['position'];
        $getTopic->is_active = $request['is_active'];
        $getTopic->slider = $request['slider'];
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
        INNER JOIN tbl_topic_category ON tbl_topic.topic_category_id = tbl_topic_category.topic_category_id");


        return response()->json(

            $getTopicSlider,
            200
        );
    }

    public function topicTestimonial(Request $request){

        $topic_id = $request['topic_id'];
    
        $topicTest = DB::select("SELECT
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
        backend.tbl_topic.topic_id = $topic_id");

        $query = DB::select("SELECT
        tbl_student.student_id,
        tbl_student.first_name,
        tbl_student.last_name,
        tbl_student.gender_id,
        tbl_student.image,
        tbl_testimonial.date_posted,
        tbl_testimonial.topic_id,
        tbl_testimonial.description,
        tbl_testimonial.testimonial_id 
    FROM
        tbl_student
        INNER JOIN tbl_testimonial ON tbl_student.student_id = tbl_testimonial.student_id 
    WHERE
        tbl_testimonial.topic_id = $topic_id");


        
    
        return response()->json(['testimonials'=>$query,'topicTestimonial'=>$topicTest]);
      }
}
