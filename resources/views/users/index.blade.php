@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Manajemen User</h3>
            <p class="text-muted mb-0">
                Kelola pengguna sistem PSL Track
            </p>
        </div>

        <a href="{{ route('users.create') }}"
           class="btn btn-primary">
            + Tambah User
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Nopeg</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Unit</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($users as $user)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                <strong>{{ $user->nama }}</strong>
                            </td>

                            <td>
                                {{ $user->nopeg }}
                            </td>

                            <td>
                                {{ $user->email }}
                            </td>

                            <td>

                                @if($user->role === 'manager')
                                    <span class="badge bg-primary">
                                        Manager
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        Staff
                                    </span>
                                @endif

                            </td>

                            <td>
                                {{ $user->unit ?? '-' }}
                            </td>

                            <td>

                                <a href="{{ route('users.edit', $user) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                @if(auth()->id() !== $user->id_user)

                                    <form action="{{ route('users.destroy', $user) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus user ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger">
                                            Hapus
                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="7"
                                class="text-center text-muted py-4">
                                Belum ada user.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@endsection