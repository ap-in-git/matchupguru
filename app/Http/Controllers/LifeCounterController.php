<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCounterController extends Controller
{
    public function index(){
        return view("user.extra.lifeCounter");
    }
}
