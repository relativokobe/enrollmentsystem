<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Student;
use App\Admin;
use App\Subject;
use App\Course;
use App\coursecoordinator;
use App\Department;
use App\Student_subject;
use App\Subject_comment;
use App\Subject_schedule;
Use App\User;

class StudentController extends Controller
{
    //P
    public function enrollSubject(Request $request){

    	$studentId = $request->input('id_number');
    	$subjectId = $request->input('subject_id');


        Student_subject::create([
        	'student_id'=>$studentId,
        	'subject_id'=>$subjectId,
        	'grade'=>0	
        	]);		

    }

    public function viewSubjNotEnrolled(Request $request){

    		$studentId = $request->input('id_number');

    			$subjects = Subject::select('subjects.subjectName','subject_schedules.id as subject_id','subject_schedules.schedule')
    							->join('subject_schedules','subject_schedules.subject_id','=','subjects.id')
    							->join('student_subjects','student_subjects.subject_id','=','subject_schedules.subject_id')
    							->where('student_subjects.student_id','!=',$studentId)
    							->get();


    			return response()->json($subjects);


    }

    public function getSched(Request $request){

    	$id = $request->input('id_number');

    	$subject = Subject_schedule::select('subject_schedules.schedule as schedule','subject_schedules.id as subject_id','subjects.subjectName')
    								->join('subjects','subjects.id','=','subject_schedules.subject_id')
    								->join('student_subjects','student_subjects.subject_id','=','subjects.id')
    								->where('student_subjects.student_id',$id)
    								->get();	

    	$array = array("subjects"=>$subject,"count"=>$subject->count());

    	return response()->json($array);

    }
   

    public function register(Request $request){

       $fname = $request->input('first_name');		
       $lname = $request->input('last_name');
       $idNum = $request->input('id_number');
       $userType = $request->input('user_type');

       $birthdate =  new \DateTime('jul 29 1997');

       $birthdate = Carbon::instance($birthdate)->toDateTimeString();


       	if($userType == 'Student'){

       	$user_id = User::create([
       	  'role'=>'Student',
       	  'email'=>$idNum+$fname,
       	  'password'=>$request->input('password'),
       	  'name'=>'zzz'
       		])->id;
       		
       	$student_id = Student::create([
       		'user_id'=>$user_id,
       		'first_name'=>$fname,
       		'last_name'=>$lname,
       		'address'=>'wala',
       		'birthdate'=>$birthdate,
       		'course_id'=>'',
       		'year'=>0,
       		'id'=>$idNum
       		])->id;

       	$dataReturned = array("data"=>"success","id"=>$student_id);
       	return response()->json($dataReturned);
      	
       }

    }
    public function viewGrade(Request $request){
    	$id = $request->input('id_number');

    	$subject = Student_subject::select('student_subjects.*','subjects.*')->join('subjects','subjects.id','=','student_subjects.subject_id')->where('student_subjects.student_id',$id)->get();

    	$array = array("subject"=>$subject,"count"=>$subject->count());

    	return response()->json($array);

    }

    public function login(Request $request){

    	$id = $request->input('id_number');
    	$password = $request->input('password');

    	$student = Student::select('students.*','users.*','students.id as student_id')
    						->join('users','users.id','=','students.user_id')
    						->where('students.id',$id)
    						->where('users.password',$password)
    						->first();

    		if($student){

    		$message = null;

    		switch($student->role){

    			case 'Student': $student = Student::where('id',$student->student_id)->first();
    							$message = array("message"=>"success","id"=>$student->id,"name"=>$student->first_name." ".$student->last_name);
    							break;

    		}

    		return response()->json($message);

    		}else{
    			$message = array("message"=>"Invalid credentials","id"=>null);

            	return response()->json($message);
    		}				

    	    	
    }


}
