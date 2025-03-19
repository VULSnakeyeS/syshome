@extends('layouts.user_type.auth')

@section('content')

@if(session('success'))
<div class="alert alert-success mx-4" id="success-alert">
    <span class="text-white">{{ session('success') }}</span>
</div>

<script>
    setTimeout(function() {
        document.getElementById('success-alert').style.display = 'none';
    }, 1000);
</script>
@endif

<div class="row">
    <div class="col-12">
        <div class="card mb-4 mx-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Inventario di {{ $animale->nome }}</h5>
                <a href="{{ route('inventarioanimali.create', $animale->id) }}" class="btn bg-gradient-primary btn-sm mb-0">+ Aggiungi Prodotto</a>
            </div>
            <div class="card-body px-4 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-4 text-center">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Categoria</th>
                                <th>Marca</th>
                                <th>Nome</th>
                                <th>Quantità</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventario as $item)
                            <tr>
                                <td>
                                    <img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('assets/img/default-product.jpg') }}" 
                                         class="avatar avatar-sm rounded-circle" 
                                         alt="Immagine prodotto">
                                </td>
                                <td>{{ $item->categoria }}</td>
                                <td>{{ $item->marca }}</td>
                                <td>{{ $item->nome }}</td>
                                <td>{{ $item->quantita }} {{ $item->unita_misura }}</td>
                                <td>
                                    <a href="{{ route('inventarioanimali.edit', [$animale->id, $item->id]) }}" class="text-secondary mx-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('inventarioanimali.destroy', [$animale->id, $item->id]) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="border-0 bg-transparent text-danger" onclick="return confirm('Eliminare questo prodotto?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">Nessun prodotto registrato.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection


