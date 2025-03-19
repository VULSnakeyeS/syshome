@extends('layouts.user_type.auth')

@section('content')

@if(session('success'))
<div class="alert alert-success mx-4" id="success-alert">
    <span class="text-white">{{ session('success') }}</span>
</div>

<script>
    setTimeout(function() {
        document.getElementById('success-alert').style.display = 'none';
    }, 5000);
</script>
@endif

<div class="row">
    <div class="col-12">
        <div class="card mb-4 mx-4">
            <div class="card-header pb-0 d-flex justify-content-between">
                <h5 class="mb-0">Gestione Utenti</h5>
                <a href="{{ route('users.create') }}" class="btn bg-gradient-primary btn-sm mb-0">+ Nuovo Utente</a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Ruolo</th>
                                <th>Stato</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td>
                                    <span class="text-xs font-weight-bold {{ $user->active ? 'text-success' : 'text-danger' }}">
                                        {!! $user->active ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-times-circle"></i>' !!}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('users.edit', $user) }}" class="mx-2 text-secondary">
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="border-0 bg-transparent text-secondary" onclick="return confirm('Eliminare utente?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

