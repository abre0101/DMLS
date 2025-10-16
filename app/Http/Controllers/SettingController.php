<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;  

class  SettingController extends Controller

{
    public function index()
    {
        $settings = Setting::all(); 

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'theme' => 'required|string',
            'email_notification' => 'required|boolean',
         
        ]);

       
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
