@extends('layouts.user_type.auth')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="container mt-4">
        <h1 class="text-primary">Modifica Servizio</h1>

        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('servizi.update', $servizio->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="servizio" class="form-label">Servizio</label>
                        <select name="servizio" class="form-control" required>
                            <option value="Acqua" {{ $servizio->servizio == 'Acqua' ? 'selected' : '' }}>Acqua</option>
                            <option value="Gas" {{ $servizio->servizio == 'Gas' ? 'selected' : '' }}>Gas</option>
                            <option value="Internet" {{ $servizio->servizio == 'Internet' ? 'selected' : '' }}>Internet</option>
                            <option value="Luce" {{ $servizio->servizio == 'Luce' ? 'selected' : '' }}>Luce</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="bol_fatt" class="form-label">Bol/Fatt</label>
                        <input type="text" name="bol_fatt" class="form-control" maxlength="50" value="{{ $servizio->bol_fatt }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="totale_fattura" class="form-label">Totale Fattura (€)</label>
                        <input type="number" name="totale_fattura" class="form-control" step="0.01" value="{{ $servizio->totale_fattura }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="data_fattura" class="form-label">Data Fattura</label>
                        <input type="date" name="data_fattura" class="form-control" value="{{ $servizio->data_fattura }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="data_scadenza" class="form-label">Data Scadenza</label>
                        <input type="date" name="data_scadenza" class="form-control" value="{{ $servizio->data_scadenza }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="pdf_path" class="form-label">Carica PDF (opzionale)</label>
                        <input type="file" name="pdf_path" class="form-control" accept="application/pdf">
                        @if($servizio->pdf_path)
                            <p class="mt-2">PDF Attuale: <a href="{{ asset('storage/' . $servizio->pdf_path) }}" target="_blank">Visualizza PDF</a></p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="commenti" class="form-label">Commenti</label>
                        <textarea name="commenti" class="form-control" rows="3">{{ $servizio->commenti }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">Salva Modifiche</button>
                        <a href="{{ route('servizi.index') }}" class="btn btn-secondary">Annulla</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection







