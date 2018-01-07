<?php

namespace FSR\Http\Controllers;

use FSR\File;
use FSR\Custom\Methods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WelcomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }


    public function index()
    {
        //$url = Methods::getFileUrl(File::first()->filename);
        return view('welcome');
    }
}
