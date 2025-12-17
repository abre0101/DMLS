<?php
// app/Http/Controllers/AdminSettingsController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;  // Assuming you have a settings model for storing these values.

class  SettingController extends Controller

{
    public function index()
    {
        // Assuming you have a 'settings' table where the app settings are stored
        $settings = Setting::all();  // Retrieve all settings

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        // Assuming you're updating settings via form fields
        $request->validate([
            'theme' => 'required|string',
            'email_notification' => 'required|boolean',
            // Add other validation rules for your settings
        ]);

        // Update the settings values
        $settings = Setting::all();

        foreach ($settings as $setting) {
            if ($setting->key === 'theme') {
                $setting->value = $request->input('theme');
            } elseif ($setting->key === 'email_notification') {
                $setting->value = $request->input('email_notification');
            }
            $setting->save();
        }

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully!');
    }
}
