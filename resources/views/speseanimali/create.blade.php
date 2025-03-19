@extends('layouts.user_type.auth')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Nuova Spesa per {{ $animale->nome }}</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('spese.store', $animale->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Data Spesa</label>
                <input type="date" name="data_spesa" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Categoria</label>
                <select name="categoria" class="form-control" required>
                    <option value="Cibo">Cibo</option>
                    <option value="Visita Veterinaria">Visita Veterinaria</option>
                    <option value="Accessori">Accessori</option>
                    <option value="Altro">Altro</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Importo (€)</label>
                <input type="number" name="importo" class="form-control" step="0.01" required>
            </div>

            <div class="mb-3">
                <label>Documento (PDF)</label>
                <input type="file" name="documento" class="form-control" accept="application/pdf">
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('spese.index', $animale->id) }}" class="btn btn-secondary me-2">🡨 Torna Indietro</a>
                <button type="submit" class="btn btn-success">Salva Spesa</button>
            </div>
        </form>
    </div>
</div>

@endsection
