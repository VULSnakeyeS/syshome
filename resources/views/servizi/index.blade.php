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

                                <!-- Botón redondo con icono plus para agregar servicio -->
                                <button type="button" class="btn btn-primary btn-icon-only rounded-circle ms-md-3 mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#addServizioModal">
                                    <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                </button>
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            <a href="{{ route('servizi.index', ['sort' => 'data_fattura']) }}">Data Fattura</a>
                                        </th>
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
                                                $colors = [
                                                    'Luce' => '#FFD700', // Amarillo
                                                    'Gas' => '#FF4500', // Rojo
                                                    'Acqua' => '#1E90FF', // Azul
                                                    'Internet' => '#32CD32' // Verde claro
                                                ];
                                            @endphp
                                            <i class="fas {{ $icons[$servizio->servizio] }} fa-lg" style="color: {{ $colors[$servizio->servizio] }};"></i>
                                        </td>
                                        <td>{{ $servizio->bol_fatt }}</td>
                                        <td>€{{ number_format($servizio->totale_fattura, 2) }}</td>
                                        <td>{{ $servizio->data_fattura }}</td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#servizioModal{{ $servizio->id }}" class="text-info me-2">
                                                <i class="fas fa-circle-info fa-lg"></i>
                                            </a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $servizio->id }}" class="text-warning me-2">
                                                <i class="fas fa-pen-to-square fa-lg"></i>
                                            </a>
                                            <form action="{{ route('servizi.destroy', $servizio->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Sei sicuro di voler eliminare questo servizio?')">
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
                            
                            <!-- PAGINACIÓN ACTUALIZADA -->
                            <div class="mt-4 d-flex justify-content-between align-items-center">
                                <div class="text-sm text-secondary">
                                    Mostrando {{ $servizi->firstItem() ?? 0 }} a {{ $servizi->lastItem() ?? 0 }} di {{ $servizi->total() }} risultati
                                </div>
                                
                                @if ($servizi->hasPages())
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination pagination-primary">
                                        <!-- Botón Anterior -->
                                        @if ($servizi->onFirstPage())
                                            <li class="page-item disabled">
                                                <a class="page-link" href="javascript:;" aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                                                </a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $servizi->previousPageUrl() }}" aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                                                </a>
                                            </li>
                                        @endif
                                        
                                        <!-- Números de Página -->
                                        @foreach ($servizi->getUrlRange(max($servizi->currentPage() - 2, 1), min($servizi->currentPage() + 2, $servizi->lastPage())) as $page => $url)
                                            <li class="page-item {{ $page == $servizi->currentPage() ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endforeach
                                        
                                        <!-- Botón Siguiente -->
                                        @if ($servizi->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $servizi->nextPageUrl() }}" aria-label="Next">
                                                    <span aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                                                </a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <a class="page-link" href="javascript:;" aria-label="Next">
                                                    <span aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modales -->
@foreach($servizi as $servizio)
<!-- Modal Dettagli -->
<div class="modal fade" id="servizioModal{{ $servizio->id }}" tabindex="-1" aria-labelledby="servizioModalLabel{{ $servizio->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="servizioModalLabel{{ $servizio->id }}">Dettagli del Servizio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> {{ $servizio->id }}</p>
                <p><strong>Servizio:</strong> {{ $servizio->servizio }}</p>
                <p><strong>Bol/Fatt:</strong> {{ $servizio->bol_fatt }}</p>
                <p><strong>Totale Fattura (€):</strong> €{{ number_format($servizio->totale_fattura, 2) }}</p>
                <p><strong>Data Fattura:</strong> {{ $servizio->data_fattura }}</p>
                <p><strong>Data Scadenza:</strong> {{ $servizio->data_scadenza }}</p>
                <p><strong>Commenti:</strong> {{ $servizio->commenti }}</p>
                @if($servizio->pdf_path)
                    <p><strong>PDF:</strong> <a href="{{ Storage::url($servizio->pdf_path) }}" target="_blank">Visualizza PDF</a></p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal{{ $servizio->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $servizio->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $servizio->id }}">Modifica Servizio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('servizi.update', $servizio->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="servizio" class="form-label">Servizio</label>
                        <select name="servizio" class="form-select">
                            @foreach ($dropdownOptions->where('dropdown_name', 'servizi') as $option)
                                <option value="{{ $option->option_value }}">{{ $option->option_value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bol_fatt" class="form-label">Bol/Fatt</label>
                        <input type="text" name="bol_fatt" class="form-control" maxlength="50" value="{{ $servizio->bol_fatt }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="totale_fattura" class="form-label">Totale Fattura (€)</label>
                        <input type="number" name="totale_fattura" class="form-control" step="0.01" value="{{ $servizio->totale_fattura }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="data_fattura" class="form-label">Data Fattura</label>
                        <input type="date" name="data_fattura" class="form-control" value="{{ $servizio->data_fattura }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="data_scadenza" class="form-label">Data Scadenza</label>
                        <input type="date" name="data_scadenza" class="form-control" value="{{ $servizio->data_scadenza }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="pdf_path" class="form-label">Carica PDF (opzionale)</label>
                        <input type="file" name="pdf_path" class="form-control" accept="application/pdf">
                        @if($servizio->pdf_path)
                            <p class="mt-2">PDF Attuale: <a href="{{ Storage::url($servizio->pdf_path) }}" target="_blank">Visualizza PDF</a></p>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="commenti" class="form-label">Commenti</label>
                        <textarea name="commenti" class="form-control" rows="3">{{ $servizio->commenti }}</textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">Salva Modifiche</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal para Agregar Nuevo Servizio -->
<div class="modal fade" id="addServizioModal" tabindex="-1" aria-labelledby="addServizioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addServizioModalLabel">Aggiungi Nuovo Servizio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                        <select name="servizio" class="form-select">
                            @foreach ($dropdownOptions->where('dropdown_name', 'servizi') as $option)
                                <option value="{{ $option->option_value }}">{{ $option->option_value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bol_fatt" class="form-label">Bol/Fatt</label>
                        <input type="text" name="bol_fatt" class="form-control" maxlength="50" required>
                    </div>
                    <div class="mb-3">
                        <label for="totale_fattura" class="form-label">Totale Fattura (€)</label>
                        <input type="number" name="totale_fattura" class="form-control" step="0.01" required>
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
                        <label for="pdf_path" class="form-label">Carica PDF (opzionale)</label>
                        <input type="file" name="pdf_path" class="form-control" accept="application/pdf">
                    </div>
                    <div class="mb-3">
                        <label for="commenti" class="form-label">Commenti</label>
                        <textarea name="commenti" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">Salva</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection