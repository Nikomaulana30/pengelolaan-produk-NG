<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(15);
        return view('menu-sidebar.user-management.user', compact('users'));
    }

    public function create()
    {
        return view('menu-sidebar.user-management.user-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,ppic,warehouse,quality',
            'is_active' => 'boolean',
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        User::create($validated);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function show(User $user)
    {
        return view('menu-sidebar.user-management.user-show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('menu-sidebar.user-management.user-edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id . '|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,ppic,warehouse,quality',
            'is_active' => 'boolean',
        ]);

        // Hash password jika ada perubahan password
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            // Jika password tidak diisi, hapus dari validated
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        return redirect()->route('user.show', $user)->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        // Cegah menghapus user yang sedang login
        if ($user->id === auth()->id()) {
            return redirect()->route('user.index')->with('error', 'Anda tidak dapat menghapus user yang sedang login!');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }
}
