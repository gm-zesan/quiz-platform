<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Update the user's profile information.
     */
    public function myProfileUpdate(ProfileUpdateRequest $request): RedirectResponse{
        $user = $request->user();
        $user->fill($request->only('name', 'email', 'phone_no', 'image'));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        if ($request->hasFile('image') && !empty($request->input('cover_image_data'))) {
            $imagePath = $request->file('image')->store('all-users', 'public');
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $user->image = $imagePath;
        }
        $user->save();
        return Redirect::route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function changePassword()
    {
        return view('profile.change-password');
    }

    /**
     * Delete the user's account.
     */
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

    public function cacheClear(): RedirectResponse
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        return Redirect::back()->with('success', 'Cache cleared successfully.');
    }
}
