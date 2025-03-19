@extends('layouts.user_type.auth')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Modifica Compito</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('compiti.update', $compito->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Compito</label>
                <select name="titolo" class="form-control" required>
                    <option value="">-- Seleziona un Compito --</option>
                    <option value="Lavare i piatti" {{ old('titolo', $compito->titolo) == 'Lavare i piatti' ? 'selected' : '' }}>Lavare i piatti</option>
                    <option value="Pulire il bagno" {{ old('titolo', $compito->titolo) == 'Pulire il bagno' ? 'selected' : '' }}>Pulire il bagno</option>
                    <option value="Passare l'aspirapolvere" {{ old('titolo', $compito->titolo) == "Passare l'aspirapolvere" ? 'selected' : '' }}>Passare l'aspirapolvere</option>
                    <option value="Cucinare" {{ old('titolo', $compito->titolo) == "Cucinare" ? 'selected' : '' }}>Cucinare</option>
                    <option value="Lavare i vestiti" {{ old('titolo', $compito->titolo) == "Lavare i vestiti" ? 'selected' : '' }}>Lavare i vestiti</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrizione</label>
                <textarea name="descrizione" class="form-control">{{ old('descrizione', $compito->descrizione) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Data del Compito</label>
                <input type="date" name="data_compito" class="form-control" value="{{ old('data_compito', $compito->data_compito) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Assegnato A</label>
                <select name="assegnato_a" class="form-control" required>
                    <option value="">-- Seleziona un Utente --</option>
                    @foreach($utenti as $utente)
                        <option value="{{ $utente->name }}" {{ old('assegnato_a', $compito->assegnato_a) == $utente->name ? 'selected' : '' }}>
                            {{ $utente->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Stato</label>
                <div class="form-check">
                    <input type="hidden" name="completato" value="0">
                    <input class="form-check-input" type="checkbox" name="completato" value="1" {{ old('completato', $compito->completato) ? 'checked' : '' }}>
                    <label class="form-check-label">Completato</label>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('compiti.index') }}" class="btn btn-secondary btn-sm me-2">🡨 Torna Indietro</a>
                <button type="submit" class="btn btn-success btn-sm">Salva Modifiche</button>
            </div>
        </form>
    </div>
</div>

@endsection



