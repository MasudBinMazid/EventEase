<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get events that are featured on home page, approved, and upcoming
        $featuredEvents = Event::featuredOnHome()->limit(6)->get();
        
        return view('home', compact('featuredEvents'));
    }
}
