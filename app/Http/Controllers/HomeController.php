<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Testimonial;
use App\Models\Topic;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function DashboardData(){
        $topics = Topic::all()->count();
        $testimonials = Testimonial::all()->count();
        $schools = School::all()->count();
        $activeTopics = Topic::where('is_active','True')->count();
        return response()->json(['topics'=>$topics,'activeTopics'=>$activeTopics,'schools'=>$schools,'testimonials'=>$testimonials]);
    }
 
}
