<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{

    // -----------------------------------------------------
    // SHOW USER'S PROFILE
    // -----------------------------------------------------

    public function show(Request $request): View
    {
        return view('admin.profile.show', [
            'user' => $request->user(),
        ]);
    }


    // -----------------------------------------------------
    // EDIT PROFILE FORM
    // -----------------------------------------------------

    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }


    // -----------------------------------------------------
    // UPDATE USER'S PROFILE INFORMATION
    // -----------------------------------------------------

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validated();

        // Check if username changed
        $data['username'] = strtolower($data['username']);
        $data['display_name'] = $request->input('username');

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('admin.profile.show')
            ->with('status', 'profile-updated');
    }


    // -----------------------------------------------------
    // EDIT PASSWORD FORM
    // -----------------------------------------------------

    public function editPassword(): View
    {
        return view('admin.profile.password', [
            'user' => auth()->user(),
        ]);
    }


    // -----------------------------------------------------
    // UPDATE USER'S PASSWORD
    // -----------------------------------------------------

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admin.profile.show')
            ->with('status', 'password-updated');
    }


    // -----------------------------------------------------
    // STORE NEW AVATAR AND UPDATE DATABASE
    // -----------------------------------------------------

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        $user = auth()->user();

        // delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // store new avatar
        $path = $request->file('avatar')->store('avatars/'.$user->id, 'public');

        // save to DB
        $user->avatar = $path;
        $user->save();

        return response()->json([
            'message' => 'Avatar updated successfully',
            'avatar_url' => Storage::url($path),
        ]);
    }


    // -----------------------------------------------------
    // DELETE USER'S ACCOUNT
    // -----------------------------------------------------

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
