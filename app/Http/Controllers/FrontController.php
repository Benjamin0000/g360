<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * show about page
     * @return \Illuminate\Http\Response
     */
    public function about()
    {
        return view('about');
    }

    /**
     * show services page
     * @return \Illuminate\Http\Response
     */
    public function services()
    {
        return view('services');
    }

    /**
     * show how it works page
     * @return \Illuminate\Http\Response
     */
    public function how_works()
    {
        return view('how_it_works');
    }

    public function terms()
    {
        
    }

}
