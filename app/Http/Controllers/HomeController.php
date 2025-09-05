<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\FeatureBanner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get events that are featured on home page, approved, and upcoming
        $featuredEvents = Event::featuredOnHome()->limit(6)->get();
        
        // Get active banners for the slider
        $banners = FeatureBanner::getActiveBanners();
        
        return view('home', compact('featuredEvents', 'banners'));
    }
}
