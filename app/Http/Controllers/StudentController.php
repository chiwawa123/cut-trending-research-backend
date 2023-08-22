<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function addStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'gender_id' => 'required',
            'image' => 'required',
        ]);
        if ($validator->fails()) {
            $response['status'] = 0;
            $response['message'] = $validator->errors()->toJson();
        } else {

            $student = new Student();
            $student->gender_id = $request['gender_id'];
            $student->first_name = $request['first_name'];
            $student->last_name = $request['last_name'];

            $fileName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/images', $fileName);
            $student->image = $fileName;
            $student->save();


            $response['status'] = 200;
            $response['message'] = 'Student was added successfully';
        }
        return response()->json($response);
    }

    public function viewStudents(Request $request)
    {

        $students = DB::select("SELECT
        backend.tbl_gender.gender_name,
        backend.tbl_student.gender_id,
        backend.tbl_student.last_name,
        backend.tbl_student.student_id,
        backend.tbl_student.first_name,
        backend.tbl_student.student_id 
    FROM
        backend.tbl_student
        INNER JOIN backend.tbl_gender ON backend.tbl_student.gender_id = backend.tbl_gender.gender_id");

        return response()->json($students, 200);
    }


    public function deleteStudent(Request $request)
    {
        
        $getStudent = $request['student_id'];

        $deleteStudent = Student::findorfail($getStudent);
        if(!$deleteStudent){
            $response['status']=0;
            $response['message']= 'student not found';
            $response['code']=404;
        }else{
            $deleteStudent->delete();
            $response['status']=200;
            $response['message']= 'student was removed Successfully';
            $response['code']=200;

        }

        return response()->json($response);
    }

    public function updateStudent(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'image' => 'required',
            'gender_id' => 'required'
        ]);

        $student_id = $request['student_id'];
        $getStudent = Student::findorfail($student_id);


        $fileName = '';

        if ($request->hasFile('image')) {
            $fileName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/images', $fileName);

            if ($getStudent->image) {
                Storage::delete('public/images/' . $getStudent->image);
            }
        } else {
            $fileName = $getStudent->image;
        }

        $getStudent->first_name = $request['first_name'];
        $getStudent->last_name = $request['last_name'];
        $getStudent->gender_id = $request['gender_id'];
        $getStudent->image = $fileName;


        if ($getStudent->save()) {
            return response()->json(['message' => 'Student  was updated successfully', 'status' => 200]);
        } else {
            return response()->json(['message' => 'Ooops Something went wrong'], 200);
        }
    }
}
