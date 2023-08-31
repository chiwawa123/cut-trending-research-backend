<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\studentAuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\TopicCategoryController;
use App\Http\Controllers\TopicController;
use App\Models\TopicCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([

    'middleware' => 'auth:api'

], function ($router) {

    
   
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');



    ///////////////////////////// Topic routes ///////////////////////////////////////////

    Route::get('viewTopics', [TopicController::class, 'viewTopics']);
    Route::post('deleteTopic', [TopicController::class, 'deleteTopic']);
 
  


    ////////////////////////////// School /////////////////////////////////////////////////
    Route::get('viewSchool', [SchoolController::class, 'viewSchool']);


    

    ////////////////////////////// Testimonial /////////////////////////////////////////////
    Route::get('viewTestimonial', [TestimonialController::class, 'viewTestimonials']);
    Route::post('addTestimonial', [TestimonialController::class, 'addTestimonial']);
    Route::post('deleteTestimonial',[TestimonialController::class,'deleteTestimonial']);
    Route::post('updateTestimonial',[TestimonialController::class,'updateTestimonial']);


    
    

    ////////////////////////////// Topic Category ///////////////////////////////////////////
    Route::get('viewTopicCategory', [TopicCategoryController::class, 'viewTopicCategory']);
    Route::post('addTopicCategory', [TopicCategoryController::class, 'addTopicCategory']);
    Route::post('deleteCategory',[TopicCategoryController::class,'deleteCategory']);
    Route::post('updateTopicCategory',[TopicCategoryController::class,'updateTopicCategory']);
   


    ////////////////////////////// Gender ////////////////////////////////////////////////////

    Route::post('addGender', [GenderController::class, 'addGender']);
    Route::get('viewGender', [GenderController::class, 'viewGender']);
    Route::post('deleteGender',[GenderController::class,'deleteGender']);
    Route::post('updateGender',[GenderController::class,'updateGender']);

    ////////////////////////////////// Student ///////////////////////////////////////////////


    Route::post('deleteStudent',[StudentController::class,'deleteStudent']);
    Route::get('viewStudents', [StudentController::class, 'viewStudents']);



    ////////////////////////////////////////// Reviews ////////////////////////////////////////


    Route::get('viewReview',[ReviewController::class, 'viewReview']);



    //////////////////////////////////////// Dashboard Data ///////////////////////////////////
    Route::get('dashData',[HomeController::class,'DashboardData']);

});
Route::post('loginStudent', [studentAuthController::class, 'login']);



Route::post('login', [AuthController::class, 'login']);


Route::middleware(
    'auth:api-student')->group( function(){
        Route::post('addReview',[ReviewController::class, 'addReview']);
    });

Route::get('topicSlider', [TopicController::class, 'topicSlider']);
Route::post('topicTestimonial',[TopicController::class,'topicTestimonial']);
Route::post('review',[ReviewController::class,'likedDisliked']);
Route::get('schoolView', [SchoolController::class, 'schoolView']);
Route::post('schoolData', [SchoolController::class,'getSchoolTopic']);

Route::post('addTopic', [TopicController::class, 'addTopic']);
Route::post('updateTopic', [TopicController::class, 'updateTopic']);

Route::post('addStudent', [StudentController::class, 'addStudent']);
Route::post('updateStudent', [StudentController::class, 'updateStudent']);
Route::post('checkReview',[ReviewController::class,'checkReview']);