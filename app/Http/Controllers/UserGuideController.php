<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserGuideController extends Controller
{
    public function __construct()
    {
        $this->__route = 'userguide';
        $this->pagetitle = 'User Guide';
    }

    public function index()
    {
        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'User Guide - Manual Book'
        ]);
    }

}
