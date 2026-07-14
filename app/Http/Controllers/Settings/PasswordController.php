<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Display the update password form.
     */
    public function show()
    {
        return view('pages.settings');
    }

    /**
     * Update the user password.
     */
    public function update(UpdatePasswordRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }
}
