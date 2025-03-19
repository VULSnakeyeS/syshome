@extends('layouts.user_type.auth')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Nuovo Prodotto per {{ $animale->nome }}</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('inventarioanimali.store', $animale->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Categoria</label>
                    <select name="categoria" class="form-control form-control-sm" required>
                        @foreach($categorie as $categoria)
                            <option value="{{ $categoria }}">{{ $categoria }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Marca</label>
                    <input type="text" name="marca" class="form-control form-control-sm" required>
                </div>
            </div>

            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control form-control-sm" required>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Quantità</label>
                    <input type="number" name="quantita" id="quantita" class="form-control form-control-sm" step="0.01" required oninput="calcolaTotale()">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Prezzo (€)</label>
                    <input type="number" name="prezzo" id="prezzo" class="form-control form-control-sm" step="0.01" required oninput="calcolaTotale()">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Totale (€)</label>
                    <input type="text" id="totale" class="form-control form-control-sm bg-light" readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Data di Acquisto</label>
                    <input type="date" name="data_acquisto" class="form-control form-control-sm" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Data di Scadenza</label>
                    <input type="date" name="data_scadenza" id="data_scadenza" class="form-control form-control-sm">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="no_scadenza" name="no_scadenza">
                        <label class="form-check-label" for="no_scadenza">Nessuna Scadenza</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label>Unità di Misura</label>
                <input type="text" name="unita_misura" class="form-control form-control-sm" required>
            </div>

            <div class="mb-3">
                <label>Foto</label>
                <input type="file" name="foto" class="form-control form-control-sm">
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('inventarioanimali.index', $animale->id) }}" class="btn btn-secondary btn-sm me-2">🡨 Torna Indietro</a>
                <button type="submit" class="btn btn-success btn-sm">Salva Prodotto</button>
            </div>
        </form>
    </div>
</div>

<script>
    function calcolaTotale() {
        let quantita = parseFloat(document.getElementById('quantita').value) || 0;
        let prezzo = parseFloat(document.getElementById('prezzo').value) || 0;
        let totale = quantita * prezzo;
        document.getElementById('totale').value = totale.toFixed(2);
    }

    document.getElementById('no_scadenza').addEventListener('change', function() {
        document.getElementById('data_scadenza').disabled = this.checked;
        if (this.checked) {
            document.getElementById('data_scadenza').value = '';
        }
    });
</script>

@endsection




