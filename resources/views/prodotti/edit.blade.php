@extends('layouts.user_type.auth')

@section('content')

<div class="container">
    <h4 class="mb-4">Modifica Prodotto</h4>

    <form method="POST" action="{{ url('prodotti/'.$prodotto->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nome" class="form-label">Nome Prodotto</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ $prodotto->nome }}" required>
        </div>

        <div class="mb-3">
            <label for="marca" class="form-label">Marca</label>
            <input type="text" class="form-control" id="marca" name="marca" value="{{ $prodotto->marca }}" required>
        </div>

        <div class="mb-3">
            <label for="categoria" class="form-label">Categoria</label>
            <input type="text" class="form-control" id="categoria" name="categoria" value="{{ $prodotto->categoria }}" required>
        </div>

        <div class="mb-3">
            <label for="barcode" class="form-label">Barcode</label>
            <input type="text" class="form-control" id="barcode" name="barcode" value="{{ $prodotto->barcode }}" required readonly>
        </div>

        <div class="mb-3">
            <label for="quantita" class="form-label">Quantità</label>
            <input type="number" class="form-control" id="quantita" name="quantita" value="{{ $prodotto->quantita }}" min="1" required>
        </div>

        <div class="mb-3">
            <label for="ubicazione" class="form-label">Ubicazione</label>
            <select class="form-control" id="ubicazione" name="ubicazione">
                <option value="cucina" {{ $prodotto->ubicazione == 'cucina' ? 'selected' : '' }}>Cucina</option>
                <option value="sala" {{ $prodotto->ubicazione == 'sala' ? 'selected' : '' }}>Sala</option>
                <option value="stanza" {{ $prodotto->ubicazione == 'stanza' ? 'selected' : '' }}>Stanza</option>
                <option value="bagno" {{ $prodotto->ubicazione == 'bagno' ? 'selected' : '' }}>Bagno</option>
                <option value="altro" {{ $prodotto->ubicazione == 'altro' ? 'selected' : '' }}>Altro</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="minimo_scorta" class="form-label">Minimo Scorta</label>
            <input type="number" class="form-control" id="minimo_scorta" name="minimo_scorta" value="{{ $prodotto->minimo_scorta }}" min="1">
        </div>

        <div class="mb-3">
            <label for="note" class="form-label">Note</label>
            <textarea class="form-control" id="note" name="note" rows="3">{{ $prodotto->note }}</textarea>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Aggiorna Prodotto</button>
            <a href="{{ route('prodotti.index') }}" class="btn btn-secondary">Annulla</a>
        </div>
    </form>
</div>

@endsection