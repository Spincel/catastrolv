<?php

namespace App\Http\Controllers;

use App\Models\Setting; // Import the Setting model
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // Assuming the logo URL is stored in the 'settings' table with a key 'institution_logo'
        $appLogo = Setting::get('institution_logo', 'img/logo.png'); // Provide a default if not found

        return view('welcome', compact('appLogo'));
    }
}
