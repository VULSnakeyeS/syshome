@extends('layouts.user_type.auth')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Dettagli Animale</h4>
        <a href="{{ route('animali.index') }}" class="btn btn-secondary btn-sm">🡨 Torna Indietro</a>
    </div>

    <div class="card-body">
        <div class="row">
            <!-- Columna con la imagen del animal -->
            <div class="col-md-4 text-center">
                <img src="{{ asset($animale->foto ? 'storage/' . $animale->foto : 'assets/img/default-animal.jpg') }}" 
                     class="img-fluid rounded-circle mb-3" 
                     alt="Immagine animale" width="200">
            </div>

            <!-- Columna con la información del animal -->
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

        <hr>

        <!-- 📋 Sección de Visitas al Veterinario/Peluquería -->
        <h5 class="mt-4">📋 Visite Veterinarie / Toelettatura</h5>
        <a href="{{ route('visite.create', $animale->id) }}" class="btn btn-sm btn-primary mb-3">+ Aggiungi Visita</a>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Data Visita</th>
                    <th>Tipo</th>
                    <th>Descrizione</th>
                    <th>Documento</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @forelse($animale->visiteVeterinarie as $visita)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($visita->data_visita)->format('d/m/Y') }}</td>
                    <td>{{ $visita->tipo }}</td>
                    <td>{{ $visita->descrizione ?? 'N/A' }}</td>
                    <td>
                        @if($visita->documento)
                            <a href="{{ asset('storage/' . $visita->documento) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-file-alt"></i>
                            </a>
                        @else
                            Nessun documento
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('visite.edit', [$animale->id, $visita->id]) }}" class="text-secondary mx-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('visite.destroy', [$animale->id, $visita->id]) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="border-0 bg-transparent text-danger" onclick="return confirm('Eliminare visita?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">Nessuna visita registrata.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <hr>

        <!-- 💰 Sección de Gastos (Spese Animali) -->
        <h5 class="mt-4">💰 Spese Animali</h5>
        <a href="{{ route('spese.create', $animale->id) }}" class="btn btn-sm btn-warning mb-3">+ Aggiungi Spesa</a>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Categoria</th>
                    <th>Descrizione</th>
                    <th>Importo (€)</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @forelse($animale->spese as $spesa)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($spesa->data_spesa)->format('d/m/Y') }}</td>
                    <td>{{ $spesa->categoria }}</td>
                    <td>{{ $spesa->descrizione ?? 'N/A' }}</td>
                    <td>€ {{ number_format($spesa->importo, 2, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('spese.edit', [$animale->id, $spesa->id]) }}" class="text-secondary mx-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('spese.destroy', [$animale->id, $spesa->id]) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="border-0 bg-transparent text-danger" onclick="return confirm('Eliminare spesa?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">Nessuna spesa registrata.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <hr>

        <!-- 📦 Sección de Inventario Animali -->
        <h5 class="mt-4">📦 Inventario Animali</h5>
        <a href="{{ route('inventario.create', $animale->id) }}" class="btn btn-sm btn-success mb-3">+ Aggiungi Prodotto</a>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Nome</th>
                    <th>Quantità</th>
                    <th>Data Acquisto</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @forelse($animale->inventario as $prodotto)
                <tr>
                    <td>{{ $prodotto->categoria }}</td>
                    <td>{{ $prodotto->nome }}</td>
                    <td>{{ $prodotto->quantita }} {{ $prodotto->unita_misura }}</td>
                    <td>{{ \Carbon\Carbon::parse($prodotto->data_acquisto)->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('inventario.edit', [$animale->id, $prodotto->id]) }}" class="text-secondary mx-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('inventario.destroy', [$animale->id, $prodotto->id]) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="border-0 bg-transparent text-danger" onclick="return confirm('Eliminare prodotto?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">Nessun prodotto registrato.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@endsection



