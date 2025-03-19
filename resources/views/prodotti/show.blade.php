@extends('layouts.user_type.auth')

@section('content')

<div class="container">
    <h4 class="mb-4">Dettagli Prodotto</h4>

    <div class="mb-3">
        <strong>Nome:</strong> {{ $prodotto->nome }}
    </div>

    <div class="mb-3">
        <strong>Marca:</strong> {{ $prodotto->marca }}
    </div>

    <div class="mb-3">
        <strong>Categoria:</strong> {{ $prodotto->categoria }}
    </div>

    <div class="mb-3">
        <strong>Barcode:</strong> {{ $prodotto->barcode }}
    </div>

    <div class="mb-3">
        <strong>Quantità:</strong> {{ $prodotto->quantita }}
    </div>

    <a href="{{ route('prodotti.index') }}" class="btn btn-secondary">Torna Indietro</a>
</div>

@endsection