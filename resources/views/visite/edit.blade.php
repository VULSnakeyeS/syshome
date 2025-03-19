@extends('layouts.user_type.auth')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Modifica Visita per {{ $animale->nome }}</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('visite.update', [$animale->id, $visita->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Data Visita</label>
                <input type="date" name="data_visita" class="form-control" value="{{ $visita->data_visita }}" required>
            </div>

            <div class="mb-3">
                <label>Tipo Visita</label>
                <input type="text" name="tipo" class="form-control" value="{{ $visita->tipo }}" required>
            </div>

            <div class="mb-3">
                <label>Descrizione</label>
                <textarea name="descrizione" class="form-control">{{ $visita->descrizione }}</textarea>
            </div>

            <div class="mb-3">
                <label>Documento (PDF o Immagine)</label>
                <input type="file" name="documento" class="form-control" accept="application/pdf,image/*">
                @if($visita->documento)
                    <p class="mt-2">Documento attuale: 
                        <a href="{{ asset('storage/' . $visita->documento) }}" target="_blank" class="text-primary">
                            <i class="fas fa-file-pdf fa-lg"></i>
                        </a>
                    </p>
                @else
                    <p class="text-muted mt-2">Nessun documento caricato</p>
                @endif
            </div>

            <div class="d-flex justify-content-end"> <!-- 🔹 Eliminado el botón de arriba -->
                <a href="{{ route('visite.index', $animale->id) }}" class="btn btn-secondary me-2">🡨 Torna Indietro</a>
                <button type="submit" class="btn btn-success">Salva Modifiche</button>
            </div>
        </form>
    </div>
</div>

@endsection

