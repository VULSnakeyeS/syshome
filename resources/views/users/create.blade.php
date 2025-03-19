@extends('layouts.user_type.auth')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Crea Utente</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Ruolo</label>
                <select name="role" class="form-control" required>
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="user">User</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Stato</label>
                <div class="form-check">
                    <input type="hidden" name="active" value="0"> <!-- ✅ Siempre envía "0" si el checkbox está desmarcado -->
                    <input class="form-check-input" type="checkbox" name="active" value="1" checked>
                    <label class="form-check-label">Attivo</label>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm me-2">🡨 Torna Indietro</a>
                <button type="submit" class="btn btn-success btn-sm">Crea Utente</button>
            </div>
        </form>
    </div>
</div>

@endsection

