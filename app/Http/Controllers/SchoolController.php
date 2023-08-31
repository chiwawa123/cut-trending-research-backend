<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    public function viewSchool()
    {
        $getSchools = School::all();

        return response()->json($getSchools, 200);
    }

    public function getSchoolTopic(Request $request)
    {
        $getSchoolTopic_id = $request['school_id'];

        $query = DB::select("
        SELECT
	*,
	tbl_school.school_name,
	tbl_school.school_id,
	tbl_topic_category.topic_category_name,
	tbl_topic_category.topic_category_id,
	tbl_topic.topic_id,
	tbl_topic.topic_name,
	tbl_topic.topic_category_id,
	tbl_topic.description,
	tbl_topic.image,
	tbl_topic.is_active 
FROM
	tbl_school
	INNER JOIN tbl_topic_category ON tbl_school.school_id = tbl_topic_category.school_id
	INNER JOIN tbl_topic ON tbl_topic_category.topic_category_id = tbl_topic.topic_category_id 
WHERE
	tbl_school.school_id = $getSchoolTopic_id");

    return response()->json($query);
    }


	
    public function schoolView()
    {
        $getSchools = School::all();

        return response()->json($getSchools, 200);
    }

   
}
