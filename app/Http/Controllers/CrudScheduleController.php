<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrudScheduleController extends Controller
{
    public function index(){
        return view('Schedule.index');
    }
}
