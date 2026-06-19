<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Validation\Rule;        

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
{
    $user = auth()->user(); 

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required', 
            'string', 
            'lowercase', 
            'email', 
            'max:255', 
            
            \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user)
        ],
        'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], 
    ]);

    $user->name = $validated['name'];
    $user->email = $validated['email'];

    if ($request->hasFile('avatar')) {
       
        if ($user->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        
        $user->avatar = $path;
    }

    $user->save();

    return redirect()->route('profile.edit')->with('status', 'profile-updated');
}

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function passwordPage()
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => [
                'required'
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8'
            ]
        ]);

        if (
            !Hash::check(
                $request->current_password,
                auth()->user()->password
            )
        ) {

            return back()->withErrors([
                'current_password' =>
                    'Current password is incorrect.'
            ]);
        }

        auth()->user()->update([
            'password' =>
                Hash::make($request->password)
        ]);

        return back()->with(
            'success',
            'Password updated successfully.'
        );
    }
}   