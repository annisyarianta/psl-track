<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('unit')
            ->orderBy('nama')
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $units = Unit::orderBy('nama_unit')->get();

        return view('users.create', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],

            'nopeg' => [
                'required',
                'string',
                'max:4',
                'unique:users,nopeg',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                Rule::in(['manager', 'asmen', 'staff']),
            ],

            'id_unit' => [
                'nullable',
                'exists:unit,id_unit',
            ],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // User baru wajib mengganti password pada login pertama
        $validated['must_change_password'] = true;

        User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load([
            'unit',
            'picStaff',
        ]);

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $units = Unit::orderBy('nama_unit')->get();

        return view('users.edit', compact('user', 'units'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],

            'nopeg' => [
                'required',
                'string',
                'max:4',
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

            'id_unit' => [
                'nullable',
                'exists:unit,id_unit',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        /*
         * Password hanya diubah jika field password diisi.
         */
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
            $validated['must_change_password'] = true;
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}