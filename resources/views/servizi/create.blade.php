@extends('layouts.user_type.auth')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>Aggiungi Servizio</h6>
                        <a href="{{ route('servizi.index') }}" class="btn btn-primary">Torna ai Servizi</a>
                    </div>
                    <div class="card-body px-4 pt-0 pb-4">
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('servizi.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="servizio" class="form-label">Servizio</label>
                                <select name="servizio" class="form-control" required>
                                    <option value="">Seleziona un servizio</option>
                                    <option value="Acqua">Acqua</option>
                                    <option value="Gas">Gas</option>
                                    <option value="Internet">Internet</option>
                                    <option value="Luce">Luce</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="bol_fatt" class="form-label">Bol/Fatt</label>
                                <input type="text" name="bol_fatt" class="form-control" maxlength="50" required>
                            </div>

                            <div class="mb-3">
                                <label for="totale_fattura" class="form-label">Totale Fattura (€)</label>
                                <input type="number" name="totale_fattura" class="form-control" step="0.01" maxlength="20" required>
                            </div>

                            <div class="mb-3">
                                <label for="data_fattura" class="form-label">Data Fattura</label>
                                <input type="date" name="data_fattura" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="data_scadenza" class="form-label">Data Scadenza</label>
                                <input type="date" name="data_scadenza" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="pdf_path" class="form-label">Carica PDF</label>
                                <input type="file" name="pdf_path" class="form-control" accept="application/pdf">
                            </div>

                            <div class="mb-3">
                                <label for="commenti" class="form-label">Commenti</label>
                                <textarea name="commenti" class="form-control"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Salva</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
