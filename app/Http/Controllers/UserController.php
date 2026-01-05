<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with('avatar');

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(10);

        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        return view('pages.users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user,super_admin',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Upload avatar menggunakan metode dari Model
        if ($request->hasFile('avatar')) {
            $user->uploadAvatar($request->file('avatar'));
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    // ===== METHOD SHOW YANG HILANG =====
    public function show($id)
    {
        $user = User::with('avatar')->findOrFail($id);

        // Tambahkan data tambahan untuk view
        $user->avatar_url = $user->avatar_url ?? asset('assets/img/default-avatar.png');
        $user->role_badge_class = match($user->role) {
            'super_admin' => 'bg-danger',
            'admin' => 'bg-warning text-dark',
            'user' => 'bg-info',
            default => 'bg-secondary'
        };
        $user->status_badge_class = $user->email_verified_at ? 'bg-success' : 'bg-warning text-dark';
        $user->status_label = $user->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi';

        return view('pages.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with('avatar')->findOrFail($id);

        // Tambahkan data untuk view
        $user->foto_profil_url = $user->avatar_url ?? asset('assets/img/default-avatar.png');

        return view('pages.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user,super_admin',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_avatar' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

      $user->update($data);

    // Hapus avatar jika checkbox delete dicentang
    if ($request->has('delete_avatar') && $request->delete_avatar == 1 && $user->avatar) {
        Storage::disk('public')->delete($user->avatar->file_url);
        $user->avatar->delete();
    }

    // Upload avatar baru (Method di Model User otomatis menghapus yang lama jika ada)
    if ($request->hasFile('avatar')) {
        $user->uploadAvatar($request->file('avatar'));
    }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Cegah menghapus diri sendiri
        if (auth()->id() == $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        // Hapus semua file media terkait
        $mediaFiles = Media::where('ref_table', 'users')
                          ->where('ref_id', $id)
                          ->get();

        foreach ($mediaFiles as $media) {
            Storage::disk('public')->delete($media->file_url);
            $media->delete();
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }

    // ===== METHOD TAMBAHAN UNTUK ROUTES =====

    /**
     * Hapus foto profil user
     */
    public function deleteFotoProfil($id)
    {
        $user = User::findOrFail($id);

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar->file_url);
            $user->avatar->delete();

            return redirect()->route('users.edit', $id)
                ->with('success', 'Foto profil berhasil dihapus');
        }

        return redirect()->route('users.edit', $id)
            ->with('error', 'Tidak ada foto profil untuk dihapus');
    }

    /**
     * Verifikasi email user
     */
    public function verify($id)
    {
        $user = User::findOrFail($id);

        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();

            return redirect()->route('users.show', $id)
                ->with('success', 'Email user berhasil diverifikasi');
        }

        return redirect()->route('users.show', $id)
            ->with('info', 'Email user sudah terverifikasi sebelumnya');
    }
}
