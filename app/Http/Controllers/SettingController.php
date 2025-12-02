<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        return view('settings.edit');
    }

    public function update(Request $request)
    {
        // 1. Guardar Enlaces de Redes Sociales y Web
        $data = $request->only(['institution_name', 'link_facebook', 'link_twitter', 'link_website']);
        
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // 2. Guardar Logo (Si se subió uno nuevo)
        if ($request->hasFile('institution_logo')) {
            $file = $request->file('institution_logo');
            $path = $file->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'institution_logo'], ['value' => $path]);
        }

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}