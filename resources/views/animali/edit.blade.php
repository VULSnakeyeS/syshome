@extends('layouts.user_type.auth')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Modifica Animale</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('animali.update', $animale->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3 text-center">
                <img src="{{ $animale->foto ? asset('storage/' . $animale->foto) : asset('assets/img/default-animal.jpg') }}" 
                     class="img-fluid rounded-circle mb-3" 
                     alt="Immagine animale" width="200">
            </div>

            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control" value="{{ old('nome', $animale->nome) }}" required>
            </div>

            <div class="mb-3">
                <label>Specie</label>
                <select name="specie" class="form-control" required>
                    <option value="Cane" {{ old('specie', $animale->specie) == 'Cane' ? 'selected' : '' }}>Cane</option>
                    <option value="Gatto" {{ old('specie', $animale->specie) == 'Gatto' ? 'selected' : '' }}>Gatto</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Razza</label>
                <input type="text" name="razza" class="form-control" value="{{ old('razza', $animale->razza) }}">
            </div>

            <div class="mb-3">
                <label>Data di Nascita</label>
                <input type="date" name="data_nascita" class="form-control" value="{{ old('data_nascita', $animale->data_nascita) }}">
            </div>

            <div class="mb-3">
                <label>Note</label>
                <textarea name="note" class="form-control">{{ old('note', $animale->note) }}</textarea>
            </div>

            <div class="mb-3">
                <label>Foto</label>
                <input type="file" name="foto" class="form-control">
                <small class="text-muted">Seleziona una nuova immagine solo se desideri cambiarla.</small>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('animali.index') }}" class="btn btn-secondary me-2">🡨 Torna Indietro</a>
                <button type="submit" class="btn btn-success">Salva Modifiche</button>
            </div>
        </form>
    </div>
</div>

@endsection







