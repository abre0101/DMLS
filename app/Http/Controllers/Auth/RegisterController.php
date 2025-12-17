<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department; // Import the Department model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {

        $departments = Department::all();


        if ($departments->isEmpty()) {
            dd('No departments found'); 
        }

        return view('auth.register', compact('departments'));
    }

    public function register(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'department_id' => 'required|exists:departments,id', // Ensure department_id is provided and exists in departments table
        ]);

      
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Hash the password using Hash facade
            'role_id' => 4, 
            'department_id' => $validatedData['department_id'], // Store the department ID
        ]);

        // Optionally, fire the Registered event
        event(new Registered($user));

        // Redirect or return response
        return redirect()->route('login')->with('success', 'Registration successful. Please log in.');
    }
}