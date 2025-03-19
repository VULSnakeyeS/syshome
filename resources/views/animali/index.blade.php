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
                <h5 class="mb-0">Gestione Animali</h5>
                <a href="{{ route('animali.create') }}" class="btn bg-gradient-primary btn-sm mb-0">+ Nuovo Animale</a>
            </div>
            <div class="card-body px-4 pt-0 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-4 text-center">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Nome</th>
                                <th>Specie</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($animali as $animale)
                            <tr>
                                <td>
                                    <img src="{{ $animale->foto ? asset('storage/' . $animale->foto) : asset('assets/img/default-animal.jpg') }}" 
                                         class="avatar avatar-sm rounded-circle" 
                                         alt="Immagine animale">
                                </td>
                                <td>{{ $animale->nome }}</td>
                                <td>{{ $animale->specie }}</td>
                                <td>
                                    <a href="#" class="text-secondary mx-2" data-bs-toggle="modal" data-bs-target="#animalModal-{{ $animale->id }}">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('animali.edit', $animale->id) }}" class="text-secondary mx-2">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="{{ route('visite.index', $animale->id) }}" class="text-secondary mx-2">
                                        <i class="fas fa-notes-medical"></i>
                                    </a>

                                    <form action="{{ route('animali.destroy', $animale->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="border-0 bg-transparent text-danger" onclick="return confirm('Eliminare animale?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- ✅ Modal Popup -->
                            <div class="modal fade" id="animalModal-{{ $animale->id }}" tabindex="-1" aria-labelledby="animalModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Dettagli di {{ $animale->nome }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-4 text-center">
                                                    <img src="{{ asset($animale->foto ? 'storage/' . $animale->foto : 'assets/img/default-animal.jpg') }}" 
                                                         class="img-fluid rounded-circle mb-3" 
                                                         alt="Immagine animale" width="150">
                                                </div>

                                                <div class="col-md-8">
                                                    <ul class="list-group">
                                                        <li class="list-group-item"><strong>Nome:</strong> {{ $animale->nome }}</li>
                                                        <li class="list-group-item"><strong>Specie:</strong> {{ $animale->specie }}</li>
                                                        <li class="list-group-item"><strong>Razza:</strong> {{ $animale->razza ?? 'N/A' }}</li>
                                                        <li class="list-group-item"><strong>Data di Nascita:</strong> 
                                                            {{ $animale->data_nascita ? \Carbon\Carbon::parse($animale->data_nascita)->format('d/m/Y') : 'N/A' }}
                                                        </li>
                                                        <li class="list-group-item"><strong>Note:</strong> {{ $animale->note ?? 'Nessuna nota' }}</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <!-- ✅ Última Visita -->
                                            @php
                                                $ultimaVisita = $animale->visiteVeterinarie->sortByDesc('data_visita')->first();
                                                $ultimoInventario = $animale->inventario->sortByDesc('data_acquisto')->first();
                                            @endphp

                                            @if($ultimaVisita)
                                            <h5 class="mt-3">📋 Ultima Visita</h5>
                                            <ul class="list-group">
                                                <li class="list-group-item"><strong>Data:</strong> {{ \Carbon\Carbon::parse($ultimaVisita->data_visita)->format('d/m/Y') }}</li>
                                                <li class="list-group-item"><strong>Tipo:</strong> {{ $ultimaVisita->tipo }}</li>
                                            </ul>
                                            @endif

                                            <!-- ✅ Último Inventario -->
                                            @if($ultimoInventario)
                                            <h5 class="mt-3">📦 Ultimo Acquisto</h5>
                                            <ul class="list-group">
                                                <li class="list-group-item"><strong>Data:</strong> {{ \Carbon\Carbon::parse($ultimoInventario->data_acquisto)->format('d/m/Y') }}</li>
                                                <li class="list-group-item"><strong>Categoria:</strong> {{ $ultimoInventario->categoria }}</li>
                                                <li class="list-group-item"><strong>Nome:</strong> {{ $ultimoInventario->nome }}</li>
                                            </ul>
                                            @endif
                                        </div>

                                        <div class="modal-footer">
                                            <a href="{{ route('visite.index', $animale->id) }}" class="text-secondary mx-2">
                                                <i class="fas fa-notes-medical fa-lg"></i>
                                            </a>
                                            <a href="{{ route('inventarioanimali.index', $animale->id) }}" class="text-secondary mx-2">
                                                <i class="fas fa-box fa-lg"></i>
                                            </a>
                                            <a href="{{ route('speseanimali.index', ['animale' => $animale->id]) }}" class="text-secondary mx-2">
                                                <i class="fas fa-euro-sign fa-lg"></i>
                                            </a>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection















