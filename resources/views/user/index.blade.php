@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>User Management</h4>
            </div>
            <div class="card-body">
                <a href="{{ route('user.create') }}" class="btn btn-success mb-3">CREATE</a>

                {{-- Alert --}}
                @if(session('success'))
                {{-- Jika ada alert sukses --}}
                <div class="alert alert-success mb-3" role="alert">
                    {{ session('success') }}
                </div>
                @elseif(session('error'))
                {{-- Jika ada alert error --}}
                <div class="alert alert-danger mb-3" role="alert">
                   {{ session('error') }}
                </div>
                @endif

                <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">No</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $key => $user)
                        <tr>
                            <th scope="row">{{ $users->firstItem() + $key }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="d-flex">
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning me-3">EDIT</a>
                                <form method="POST" action="{{ route('user.delete', $user->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">DELETE</button>
                                </form>
                            </td>
                          </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
