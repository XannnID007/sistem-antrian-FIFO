<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display user listing (Pengasuh only)
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $users = $query->latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show create user form
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:pengasuh,admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => true,
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->log('Created new user: ' . $user->name);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display user details
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show edit user form
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:pengasuh,admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->log('Updated user: ' . $user->name);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        // Prevent deleting current user
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Prevent deleting the last pengasuh
        if ($user->role === 'pengasuh' && User::where('role', 'pengasuh')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus pengasuh terakhir.');
        }

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->log('Deleted user: ' . $user->name);

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        // Prevent deactivating current user
        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.'], 400);
        }

        // Prevent deactivating the last active pengasuh
        if (
            $user->role === 'pengasuh' && $user->is_active &&
            User::where('role', 'pengasuh')->where('is_active', true)->count() <= 1
        ) {
            return response()->json(['error' => 'Tidak dapat menonaktifkan pengasuh terakhir yang aktif.'], 400);
        }

        $user->update(['is_active' => !$user->is_active]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->log(($user->is_active ? 'Activated' : 'Deactivated') . ' user: ' . $user->name);

        return response()->json([
            'success' => true,
            'message' => 'Status user berhasil diubah.',
            'is_active' => $user->is_active
        ]);
    }

    /**
     * Reset user password
     */
    public function resetPassword(User $user)
    {
        $newPassword = 'password123'; // You might want to generate a random password
        $user->update(['password' => Hash::make($newPassword)]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->log('Reset password for user: ' . $user->name);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset.',
            'new_password' => $newPassword
        ]);
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('profile.edit')
            ->with('success', 'Password berhasil diperbarui.');
    }
}
