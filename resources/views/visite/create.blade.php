@extends('layouts.user_type.auth')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Nuova Visita per {{ $animale->nome }}</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('visite.store', $animale->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Data Visita</label>
                <input type="date" name="data_visita" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Tipo di Visita</label>
                <select name="tipo" class="form-control" required>
                    <option value="Veterinario">Veterinario</option>
                    <option value="Toelettatura">Toelettatura</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Descrizione</label>
                <textarea name="descrizione" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label>Documento (PDF)</label>
                <input type="file" name="documento" class="form-control" accept="application/pdf">
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('visite.index', $animale->id) }}" class="btn btn-secondary me-2">🡨 Torna Indietro</a>
                <button type="submit" class="btn btn-success">Salva Visita</button>
            </div>
        </form>
    </div>
</div>

@endsection

