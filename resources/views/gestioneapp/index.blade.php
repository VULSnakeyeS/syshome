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
                            <h6 class="mb-3 mb-md-0">Gestione App</h6>

                            <div class="d-flex flex-column flex-md-row align-items-md-center">
                                <!-- Filtros por fecha -->
                                <form method="GET" action="{{ route('gestioneapp.index') }}" class="d-flex flex-wrap align-items-center">
                                    <input type="date" name="data_inicio" class="form-control form-control-sm me-2 mb-2 mb-md-0" style="max-width: 150px;">
                                    <input type="date" name="data_fine" class="form-control form-control-sm me-2 mb-2 mb-md-0" style="max-width: 150px;">
                                    <button type="submit" class="btn btn-secondary btn-sm px-3" style="height: 32px;">Filtra</button>
                                </form>

                                <!-- Botón redondo con icono plus para agregar -->
                                <button type="button" class="btn btn-primary btn-icon-only rounded-circle ms-md-3 mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#addConfigModal">
                                    <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-4 pt-0 pb-4">
                        <!-- Barra de búsqueda debajo del botón "Aggiungi Configurazione" -->
                        <form method="GET" action="{{ route('gestioneapp.index') }}" class="mb-3">
                            <div class="input-group input-group-sm" style="max-width: 250px;">
                                <input type="text" class="form-control" id="search" name="search" placeholder="Cerca..." value="{{ request('search') }}">
                                <button type="submit" class="input-group-text bg-white border"><i class="fas fa-search"></i></button>
                            </div>
                        </form>

                        <!-- Tabla de configuraciones -->
                        <div class="table-responsive">
                            <table class="table align-items-center text-center">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Configurazione</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valore</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($configurazioni as $configurazione)
                                    <tr>
                                        <td>{{ $configurazione->nome }}</td>
                                        <td>{{ $configurazione->valore }}</td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $configurazione->id }}" class="text-warning me-2">
                                                <i class="fas fa-pen-to-square fa-lg"></i>
                                            </a>
                                            <form action="{{ route('gestioneapp.destroy', $configurazione->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Sei sicuro di voler eliminare questa configurazione?')">
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
                                    Mostrando {{ $configurazioni->firstItem() ?? 0 }} a {{ $configurazioni->lastItem() ?? 0 }} di {{ $configurazioni->total() }} risultati
                                </div>
                                
                                @if ($configurazioni->hasPages())
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination pagination-primary">
                                        <!-- Botón Anterior -->
                                        @if ($configurazioni->onFirstPage())
                                            <li class="page-item disabled">
                                                <a class="page-link" href="javascript:;" aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                                                </a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $configurazioni->previousPageUrl() }}" aria-label="Previous">
                                                    <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                                                </a>
                                            </li>
                                        @endif
                                        
                                        <!-- Números de Página -->
                                        @foreach ($configurazioni->getUrlRange(max($configurazioni->currentPage() - 2, 1), min($configurazioni->currentPage() + 2, $configurazioni->lastPage())) as $page => $url)
                                            <li class="page-item {{ $page == $configurazioni->currentPage() ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endforeach
                                        
                                        <!-- Botón Siguiente -->
                                        @if ($configurazioni->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $configurazioni->nextPageUrl() }}" aria-label="Next">
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
@foreach($configurazioni as $configurazione)
<!-- Modal Edit -->
<div class="modal fade" id="editModal{{ $configurazione->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $configurazione->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $configurazione->id }}">Modifica Configurazione</h5>
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
                <form action="{{ route('gestioneapp.update', $configurazione->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" name="nome" class="form-control" value="{{ $configurazione->nome }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="valore" class="form-label">Valore</label>
                        <input type="text" name="valore" class="form-control" value="{{ $configurazione->valore }}" required>
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

<!-- Modal para Agregar Nueva Configurazione -->
<div class="modal fade" id="addConfigModal" tabindex="-1" aria-labelledby="addConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addConfigModalLabel">Aggiungi Nuova Configurazione</h5>
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
                <form action="{{ route('gestioneapp.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="valore" class="form-label">Valore</label>
                        <input type="text" name="valore" class="form-control" required>
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