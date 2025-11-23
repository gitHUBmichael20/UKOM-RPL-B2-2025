<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        return $this->updateProfile($request, 'profile.edit');
    }


    public function editCustomer(Request $request): View
    {
        return view('profile.customer-edit', [
            'user' => $request->user(),
        ]);
    }

    public function updateCustomer(ProfileUpdateRequest $request): RedirectResponse
    {
        return $this->updateProfile($request, 'profile.customer.edit');
    }

    public function destroyCustomer(Request $request): RedirectResponse
    {
        return $this->destroyProfile($request);
    }

    public function deletePhotoCustomer(Request $request)
    {
        return $this->deletePhoto($request);
    }


    private function updateProfile(ProfileUpdateRequest $request, string $routeName): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $request->validate([
            'phone' => ['nullable', 'string', 'max:20'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $data['foto_profil'] = $request->file('foto_profil')->store('profile-photos', 'public');
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route($routeName)->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        return $this->destroyProfile($request);
    }

    private function destroyProfile(Request $request): RedirectResponse
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

    public function deletePhoto(Request $request)
    {
        $user = $request->user();

        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
            $user->update(['foto_profil' => null]);
        }

        return response()->json(['success' => true]);
    }
}
