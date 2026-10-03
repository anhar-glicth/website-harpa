<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit-custom', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle remove photo
        if ($request->boolean('remove_photo')) {
            if ($user->profile_photo) {
                $oldPath = str_replace('/storage/', '', $user->profile_photo);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }
            $user->profile_photo = null;
        }

        if ($request->hasFile('profile_photo')) {
            $request->validate([
                'profile_photo' => 'nullable|file|mimes:jpeg,jpg,png,webp,gif,svg|max:5120',
            ], [
                'profile_photo.file' => 'Berkas foto profil harus berupa file yang valid.',
                'profile_photo.mimes' => 'Format foto profil harus berupa JPG, JPEG, PNG, WEBP, GIF, atau SVG.',
                'profile_photo.max' => 'Ukuran foto profil tidak boleh melebihi 5MB.',
            ]);
            if ($user->profile_photo) {
                $oldPath = str_replace('/storage/', '', $user->profile_photo);
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $user->profile_photo = '/storage/' . $path;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Profil Anda berhasil diperbarui.');
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
}
