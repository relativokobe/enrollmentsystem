<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //\$
    protected $fillable = ['user_id','id',
       		'first_name',
       		'last_name',
       		'address',
       		'birthdate','course_id','year'];

     public $timestamps = false;
}
