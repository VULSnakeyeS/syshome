@extends('layouts.user_type.auth')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Modifica Prodotto</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('inventarioanimali.update', [$animale->id, $inventario->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-3">
                <label>Categoria</label>
                <select name="categoria" class="form-control" required>
                    @foreach($categorie as $categoria)
                        <option value="{{ $categoria }}" {{ $inventario->categoria == $categoria ? 'selected' : '' }}>{{ $categoria }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Marca</label>
                <input type="text" name="marca" class="form-control" value="{{ $inventario->marca }}" required>
            </div>

            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control" value="{{ $inventario->nome }}" required>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Quantità</label>
                    <input type="number" name="quantita" id="quantita" class="form-control" value="{{ $inventario->quantita }}" step="0.01" required oninput="calcolaTotale()">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Prezzo (€)</label>
                    <input type="number" name="prezzo" id="prezzo" class="form-control" value="{{ $inventario->prezzo }}" step="0.01" required oninput="calcolaTotale()">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Totale (€)</label>
                    <input type="text" id="totale" class="form-control bg-light" value="{{ number_format($inventario->quantita * $inventario->prezzo, 2, '.', '') }}" readonly>
                </div>
            </div>

            <div class="mb-3">
                <label>Unità di Misura</label>
                <input type="text" name="unita_misura" class="form-control" value="{{ $inventario->unita_misura }}" required>
            </div>

            <div class="mb-3">
                <label>Data di Acquisto</label>
                <input type="date" name="data_acquisto" class="form-control" value="{{ $inventario->data_acquisto }}">
            </div>

            <div class="mb-3">
                <label>Data di Scadenza</label>
                <input type="date" name="data_scadenza" id="data_scadenza" class="form-control" value="{{ $inventario->data_scadenza }}">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="no_expiry" {{ $inventario->data_scadenza ? '' : 'checked' }}>
                    <label class="form-check-label" for="no_expiry">Nessuna scadenza</label>
                </div>
            </div>

            <div class="mb-3">
                <label>Foto</label>
                <input type="file" name="foto" class="form-control">
                @if($inventario->foto)
                    <p class="mt-2">Foto attuale:</p>
                    <img src="{{ asset('storage/' . $inventario->foto) }}" class="img-fluid rounded" width="100">
                @endif
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('inventarioanimali.index', $animale->id) }}" class="btn btn-secondary me-2">🡨 Torna Indietro</a>
                <button type="submit" class="btn btn-success">Salva Modifiche</button>
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

    document.getElementById('no_expiry').addEventListener('change', function() {
        document.getElementById('data_scadenza').disabled = this.checked;
        if (this.checked) {
            document.getElementById('data_scadenza').value = '';
        }
    });
</script>

@endsection