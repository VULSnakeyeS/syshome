@extends('layouts.user_type.auth')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Nuovo Compito</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('compiti.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Compito</label>
                <select name="compito" class="form-select">
                    @foreach ($dropdownOptions->where('dropdown_name', 'compiti') as $option)
                        <option value="{{ $option->option_value }}">{{ $option->option_value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrizione</label>
                <textarea name="descrizione" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Data del Compito</label>
                <input type="date" name="data_compito" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Assegnato A</label>
                <select name="assegnato_a" class="form-control" required>
                    <option value="">-- Seleziona un Utente --</option>
                    @foreach($utenti as $utente)
                        <option value="{{ $utente->name }}">{{ $utente->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('compiti.index') }}" class="btn btn-secondary btn-sm me-2">🡨 Torna Indietro</a>
                <button type="submit" class="btn btn-success btn-sm">Salva Compito</button>
            </div>
        </form>
    </div>
</div>

@endsection
