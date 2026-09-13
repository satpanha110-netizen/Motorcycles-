<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StorageHelper;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::withCount(['motorcycles', 'orders'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->q.'%';
                $query->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->role))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['password'] = Hash::make($data['password']);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profiles', StorageHelper::disk());
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validated($request, $user->id, withPassword: false);

        // "Leave blank to keep current": drop the empty password key so the
        // UPDATE never writes NULL into the password column.
        unset($data['password']);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk(StorageHelper::disk())->delete($user->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('profiles', StorageHelper::disk());
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', "User \"{$user->name}\" updated.");
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->id === auth()->id(), 403, 'You cannot delete your own account.');

        if ($user->profile_image) {
            Storage::disk(StorageHelper::disk())->delete($user->profile_image);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        abort_if($user->id === auth()->id(), 403, 'You cannot deactivate your own account.');

        $user->update(['status' => $user->isActive() ? 'inactive' : 'active']);

        return back()->with('success', "User \"{$user->name}\" is now {$user->status}.");
    }

    protected function validated(Request $request, ?int $ignoreId = null, bool $withPassword = true): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users')->ignore($ignoreId)],
            'phone' => ['required', 'string', 'min:8', 'max:30', 'regex:/^[0-9+\-\s()]+$/'],
            'role' => ['required', 'in:admin,seller,customer'],
            'status' => ['required', 'in:active,inactive'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        if ($withPassword) {
            $rules['password'] = ['required', 'string', 'min:8'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:8'];
        }

        return $request->validate($rules);
    }
}
