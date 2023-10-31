<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\View;
use Illuminate\Support\Facades\DB;
class ViewsController extends Controller
{
    public function countViews(Request $request){
        $topic_id = $request['topic_id'];
    
        $count = DB::select("SELECT
        COUNT( backend.tbl_views.topic_id ) AS views 
    FROM
        backend.tbl_views 
    WHERE
        backend.tbl_views.topic_id = $topic_id");
    
        return response()->json($count);
    }
}
