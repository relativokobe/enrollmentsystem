<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student_subject extends Model
{
    //
    protected $fillable = ['student_id','subject_id','grade'
       		];

     public $timestamps = false;

}
