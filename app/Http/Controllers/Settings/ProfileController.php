<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the profile settings panel.
     */
    public function show()
    {
        $user = Auth::user();

        return view('pages.settings', compact('user'));
    }

    /**
     * Update the user profile details.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        $user->update($validated);

        return back()->with('success', 'Profile information updated successfully!');
    }
}
