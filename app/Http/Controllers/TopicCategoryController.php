<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\Topic;
use App\Models\TopicCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TopicCategoryController extends Controller
{
    public function viewTopicCategory()
    {
        // $topicCategory = TopicCategory::all();

        $topicCategory = DB::select("SELECT
        tbl_topic_category.topic_category_name, 
        tbl_school.school_name,
        tbl_topic_category.topic_category_id
    
    FROM
  
    tbl_topic_category
       
        INNER JOIN
        tbl_school
        ON 
        tbl_topic_category.school_id = tbl_school.school_id");
        

        return response()->json($topicCategory,200);
    }

    public function addTopicCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic_category_name' => 'required',
            'school_id' => 'required',
        ]);
        if ($validator->fails())
        {
            $response['status']=0;
            $response['message']= $validator->errors()->toJson();
      
        }else{
    
            $topicCategory =  new TopicCategory([
                'topic_category_name' => $request['topic_category_name'],
                'school_id' => $request['school_id'],
            ]);
            $topicCategory->save();
    
    
            $response['status']=200;
            $response['message']= 'Topic Category was added successfully';
    
        }
        return response()->json($response);

    }
    

    public function deleteCategory(Request $request){
        $getCategory_id = $request['topic_category_id'];

        $deleteCategory = TopicCategory::findorfail($getCategory_id);
        if(!$deleteCategory){
            $response['status']=404;
            $response['message']= 'Topic Category not found';
         
        }else{
            $deleteCategory->delete();
            $response['status']=200;
            $response['message']= 'Topic Category was removed Successfully';
            
    
        }

        return response()->json($response);

    }
    public function updateTopicCategory(Request $request, Exception $exception)
    {  
      $request->validate([
        'school_id' => 'required',
        'topic_category_name' => 'required',
    ]);
  
        $topic_category = $request['topic_category_id'];

        $getTopicCategory = TopicCategory::findorfail($topic_category);
  
        $getTopicCategory->school_id = $request['school_id'];
        $getTopicCategory->topic_category_name=$request['topic_category_name'];
     
  
  
        if($getTopicCategory->save()){
            return response()->json(['message'=>'Topic Category was updated successfully',
            'status'=>200]);
          }else{
            return response()->json('message',$exception->getMessage());
          }
    }

    public function getTopicCategory(Request $request){
        $topic_category_id = $request['topic_category_id'];

        $category = DB::select("SELECT
        tbl_topic.topic_name,
        tbl_topic.topic_id,
        tbl_topic.topic_category_id,
        tbl_topic.description,
        tbl_topic.image,
        tbl_topic.is_active,
        tbl_topic.date_posted,
        tbl_topic_category.topic_category_name,
        tbl_topic_category.school_id 
    FROM
        tbl_topic
        INNER JOIN tbl_topic_category ON tbl_topic.topic_category_id = tbl_topic_category.topic_category_id 
    WHERE
        tbl_topic_category.topic_category_id =  $topic_category_id");

    return response()->json($category);
    }

public function CategoryHome(){
    $topicCategory = DB::select("SELECT
    tbl_topic_category.topic_category_name, 
    tbl_school.school_name,
    tbl_topic_category.topic_category_id

FROM

tbl_topic_category
   
    INNER JOIN
    tbl_school
    ON 
    tbl_topic_category.school_id = tbl_school.school_id");
    

    return response()->json($topicCategory,200);

}
   
}
