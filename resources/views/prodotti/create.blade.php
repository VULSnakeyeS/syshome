@extends('layouts.user_type.auth')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4>Aggiungi Prodotto</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('prodotti.store') }}">
                        @csrf

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome Prodotto</label>
                            <input type="text" class="form-control" id="nome" name="nome" 
                                value="{{ old('nome', $nome ?? '') }}" required>
                        </div>

                        <!-- Marca -->
                        <div class="mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control" id="marca" name="marca" 
                                value="{{ old('marca', $marca ?? '') }}">
                        </div>

                        <!-- Categoría -->
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria</label>
                            <input type="text" class="form-control" id="categoria" name="categoria" 
                                value="{{ old('categoria', $categoria ?? '') }}" required>
                        </div>

                        <!-- Cantidad -->
                        <div class="mb-3">
                            <label for="quantita" class="form-label">Quantità</label>
                            <input type="number" class="form-control" id="quantita" name="quantita" 
                                value="{{ old('quantita', 1) }}" min="1" required>
                        </div>

                        <!-- Ubicación -->
                        <div class="mb-3">
                            <label for="ubicazione" class="form-label">Ubicazione</label>
                            <input type="text" class="form-control" id="ubicazione" name="ubicazione" 
                                value="{{ old('ubicazione', '') }}" required>
                        </div>

                        <!-- Código de Barras (readonly) -->
                        <div class="mb-3">
                            <label for="barcode" class="form-label">Codice a Barre</label>
                            <input type="text" class="form-control" id="barcode" name="barcode" 
                                value="{{ old('barcode', $barcode ?? '') }}" readonly>
                        </div>

                        <!-- Imagen -->
                        <div class="mb-3">
                            <label for="immagine" class="form-label">Immagine del Prodotto</label>
                            <input type="text" class="form-control" id="immagine" name="immagine" 
                                value="{{ old('immagine', $immagine ?? '') }}">
                            <div class="mt-2">
                                <img id="preview-img" src="{{ $immagine ?? asset('assets/img/default-product.jpg') }}" 
                                    class="img-fluid rounded" style="max-width: 150px;">
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="text-end">
                            <a href="{{ route('prodotti.index') }}" class="btn btn-secondary">Annulla</a>
                            <button type="submit" class="btn btn-primary">Salva Prodotto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para cambiar la imagen en tiempo real -->
<script>
    document.getElementById('immagine').addEventListener('input', function () {
        let imgPreview = document.getElementById('preview-img');
        imgPreview.src = this.value || "{{ asset('assets/img/default-product.jpg') }}";
    });
</script>

@endsection