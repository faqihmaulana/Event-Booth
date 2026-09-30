<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon; // Import Carbon untuk timestamp

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::all();
        return view('admin.manage-user', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'status' => $request->status,
            'email_verified_at' => Carbon::now(), // Otomatis verifikasi email
        ]);

        return redirect()->route('manage-user')->with('success', 'User berhasil ditambahkan dan email terverifikasi');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        // Jika email berubah, set ulang verifikasi email
        $updateData = $request->only('name', 'email', 'phone', 'company', 'role_id', 'status');
        
        if ($user->email !== $request->email) {
            $updateData['email_verified_at'] = Carbon::now(); // Auto-verify jika email diubah
        }

        $user->update($updateData);

        return redirect()->route('manage-user')->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('manage-user')->with('success', 'User berhasil dihapus');
    }
}