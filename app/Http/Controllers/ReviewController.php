<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function addReview(Request $request)
    {

        $topic_id = $request['topic_id'];
        $student_id = $request['student_id'];

        $reviewTopic = Review::join('tblstudent', 'tblstudent.student_id', '=', 'tbl_reviews.student_id')
            ->where('tblstudent.student_id', $student_id)
            ->where('tbl_reviews.topic_id',  $topic_id)
            ->first();

        if ($reviewTopic) {
            $reviewTopic->delete();
        } else {
            $review =  new Review();
            $review->student_id = $request['student_id'];
            $review->topic_id=$request['topic_id'];
            $review->is_liked=$request['is_liked'];

            $review->save();
        }
        return response()->json(['message'=>'review added successfully','status'=>200]);
    }

    public function viewReview()
    {
        $reviews = DB::select("SELECT
        backend.tbl_reviews.review_id,
        backend.tbl_reviews.is_liked,
        backend.tbl_reviews.dis_liked,
        backend.tbl_reviews.topic_id,
        backend.tbl_topic.topic_name,
        backend.tbl_topic.topic_id,
        backend.tbl_topic.is_active 
    FROM
        backend.tbl_reviews
        INNER JOIN backend.tbl_topic ON backend.tbl_reviews.topic_id = backend.tbl_topic.topic_id");

        return response()->json(['reviews' => $reviews], 200);
    }

    public function likedDisliked(Request $request)
    {
        $topic_id = $request['topic_id'];


        $liked = Review::where('tbl_reviews.is_liked', 1)->where('tbl_reviews.topic_id', $topic_id)
            ->join('tbl_topic', 'tbl_topic.topic_id', '=', 'tbl_reviews.topic_id')
            ->select('tbl_topic.topic_id,
                        tbl_topic.topic_name,
                        tbl_topic.topic_category_id,
                        tbl_topic.description,
                        tbl_topic.image,
                        tbl_topic.is_active,
                        tbl_topic.date_posted,
                        tbl_reviews.is_liked,
                        tbl_reviews.dis_liked,
                        tbl_reviews.review_id ')
            ->count();

        $dis_liked = Review::where('tbl_reviews.dis_liked', 1)->where('tbl_reviews.topic_id', $topic_id)
            ->join('tbl_topic', 'tbl_topic.topic_id', '=', 'tbl_reviews.topic_id')
            ->select('tbl_topic.topic_id,
                        tbl_topic.topic_name,
                        tbl_topic.topic_category_id,
                        tbl_topic.description,
                        tbl_topic.image,
                        tbl_topic.is_active,
                        tbl_topic.date_posted,
                        tbl_reviews.is_liked,
                        tbl_reviews.dis_liked,
                        tbl_reviews.review_id ')
            ->count();


        return response()->json(['liked' => $liked, 'disliked' => $dis_liked]);
    }
}
