<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function showForm()
    {
        return view('profile');
    }

    public function uploadPhoto(Request $request)
    {
        // Logique pour traiter le téléchargement de la photo
        $validator = Validator::make($request->all(), [
            'profilePhoto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('profile.form')
                             ->withErrors($validator)
                             ->withInput();
        }

        if ($request->hasFile('profilePhoto')) {
            $file = $request->file('profilePhoto');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('public/profile_photos', $fileName);

            // Vous pouvez également enregistrer le chemin dans la base de données si nécessaire
            // User::find(auth()->id())->update(['profile_photo' => $fileName]);

             // Validation et traitement de l'upload...
            return redirect()->route('profile.form')->with('success', 'Photo téléchargée avec succès.');
        }

        return redirect()->route('profile.form')->with('error', 'Erreur lors du téléchargement.');
    }
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
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
