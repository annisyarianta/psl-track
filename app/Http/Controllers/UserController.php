<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('nama')->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],

            'nopeg' => [
                'required',
                'regex:/^[0-9]{1,4}$/',
                'unique:users,nopeg',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'role' => [
                'required',
                Rule::in(['manager', 'asmen', 'staff']),
            ],

            'unit' => [
                'nullable',
                Rule::in([
                    'perencanaan',
                    'evaluasi',
                    'inovasi',
                ]),
            ],
        ]);

        User::create([
            'nama' => $validated['nama'],
            'nopeg' => $validated['nopeg'],
            'email' => $validated['email'],
            'password' => Hash::make('12345678'),
            'role' => $validated['role'],
            'unit' => $validated['unit'] ?? null,
            'must_change_password' => true,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }


    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }


    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],

            'nopeg' => [
                'required',
                'regex:/^[0-9]{1,4}$/',
                Rule::unique('users', 'nopeg')
                    ->ignore($user->id_user, 'id_user'),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id_user, 'id_user'),
            ],

            'role' => [
                'required',
                Rule::in(['manager', 'asmen', 'staff']),
            ],

            'unit' => [
                'nullable',
                Rule::in([
                    'perencanaan',
                    'evaluasi',
                    'inovasi',
                ]),
            ],
        ]);

        $user->update([
            'nama' => $validated['nama'],
            'nopeg' => $validated['nopeg'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'unit' => $validated['unit'] ?? null,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
 * Hapus user
 */
public function destroy(User $user)
{
    if (auth()->id() === $user->id_user) {
        return redirect()
            ->route('users.index')
            ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
    }

    try {
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus.');

    } catch (\Exception $e) {

        return redirect()
            ->route('users.index')
            ->with('error', 'User gagal dihapus.');
    }
}
}