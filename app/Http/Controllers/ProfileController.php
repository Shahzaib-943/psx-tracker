<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $profile)
    {
        return view('users.profile', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $profile, UpdateProfileRequest $request)
    {
        $validatedData = $request->validated();
        if (empty($validatedData['password'])) {
            unset($validatedData['password']);
        }
        if ($profile->update($validatedData)) {
            flash()->success('Profile Updated Successfully.');
        } else {
            flash()->error('An unexpected error occurred. Please try again.');
        }
        return to_route('profile.edit', $profile->public_id);
    }

}
