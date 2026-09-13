<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\StorageHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->only('name', 'email', 'phone', 'telegram_username');

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk(StorageHelper::disk())->delete($user->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('profiles', StorageHelper::disk());
        }

        if ($request->filled('password')) {
            $data['password'] = $request->input('password'); // auto-hashed by cast
        }

        $user->update($data);

        return back()->with('success', 'Your profile has been updated.');
    }
}
