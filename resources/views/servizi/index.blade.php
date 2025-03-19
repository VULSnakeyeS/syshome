@extends('layouts.user_type.auth')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <!-- Encabezado con Filtros y Botón de Agregar -->
                    <div class="card-header pb-0">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                            <h6 class="mb-3 mb-md-0">Servizi</h6>

                            <div class="d-flex flex-column flex-md-row align-items-md-center">
                                <!-- Filtros por fecha -->
                                <form method="GET" action="{{ route('servizi.index') }}" class="d-flex flex-wrap align-items-center">
                                    <input type="date" name="data_fattura_inicio" class="form-control form-control-sm me-2 mb-2 mb-md-0" style="max-width: 150px;">
                                    <input type="date" name="data_fattura_fine" class="form-control form-control-sm me-2 mb-2 mb-md-0" style="max-width: 150px;">
                                    <button type="submit" class="btn btn-secondary btn-sm px-3" style="height: 32px;">Filtra</button>
                                </form>

                                <!-- Botón de agregar servicio -->
                                <a href="{{ route('servizi.create') }}" class="btn btn-primary btn-sm ms-md-3 mt-2 mt-md-0">Aggiungi Servizio</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-4 pt-0 pb-4">
                        <!-- Barra de búsqueda debajo del botón "Aggiungi Servizio" -->
                        <form method="GET" action="{{ route('servizi.index') }}" class="mb-3">
                            <div class="input-group input-group-sm" style="max-width: 250px;">
                                <input type="text" class="form-control" id="search" name="search" placeholder="Cerca..." value="{{ request('search') }}">
                                <button type="submit" class="input-group-text bg-white border"><i class="fas fa-search"></i></button>
                            </div>
                        </form>

                        <!-- Mostrar total solo si se usa el filtro de fechas -->
                        @if(isset($totalFattura))
                            <div class="alert alert-dark text-white fw-bold">
                                Totale Speso in questo periodo: €{{ number_format($totalFattura, 2) }}
                            </div>
                        @endif

                        <!-- Tabla de servicios -->
                        <div class="table-responsive">
                            <table class="table align-items-center text-center">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Servizio</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bol/Fatt</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Totale (€)</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Data Fattura</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($servizi as $servizio)
                                    <tr>
                                        <td>
                                            @php
                                                $icons = [
                                                    'Luce' => 'fa-lightbulb',
                                                    'Gas' => 'fa-fire-flame-simple',
                                                    'Acqua' => 'fa-faucet-drip',
                                                    'Internet' => 'fa-house-signal'
                                                ];
                                            @endphp
                                            <i class="fas {{ $icons[$servizio->servizio] }} fa-lg"></i>
                                        </td>
                                        <td>{{ $servizio->bol_fatt }}</td>
                                        <td>€{{ number_format($servizio->totale_fattura, 2) }}</td>
                                        <td>{{ $servizio->data_fattura }}</td>
                                        <td>
                                            <a href="{{ route('servizi.show', $servizio->id) }}" class="text-info me-2">
                                                <i class="fas fa-circle-info fa-lg"></i>
                                            </a>
                                            <a href="{{ route('servizi.edit', $servizio->id) }}" class="text-warning me-2">
                                                <i class="fas fa-pen-to-square fa-lg"></i>
                                            </a>
                                            <form action="{{ route('servizi.destroy', $servizio->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Sei sicuro di voler eliminare questo servizio?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger border-0 bg-transparent">
                                                    <i class="fas fa-trash-can fa-lg"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
















