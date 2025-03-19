@extends('layouts.user_type.auth')

@section('content')

@if(session('success'))
<div class="alert alert-success mx-4" id="success-alert">
    <span class="text-white">{{ session('success') }}</span>
</div>

<script>
    setTimeout(function() {
        document.getElementById('success-alert').style.display = 'none';
    }, 5000);
</script>
@endif

<div class="row">
    <div class="col-12">
        <div class="card mb-4 mx-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Gestione Compiti</h5>
                <a href="{{ route('compiti.create') }}" class="btn bg-gradient-primary btn-sm mb-0">+ Nuovo Compito</a>
            </div>
            <div class="card-body px-4 pt-0 pb-2">
                <div class="d-flex justify-content-between mb-3">
                    <input type="text" id="search" class="form-control w-25" placeholder="🔍 Cerca Compito">
                    <input type="date" id="filter-date" class="form-control w-25">
                </div>

                <h6 class="text-primary">📌 Compiti da Fare</h6>
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-4 text-center">
                        <thead>
                            <tr>
                                <th>Titolo</th>
                                <th>Assegnato A</th>
                                <th>Data</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compiti_pendenti as $compito)
                            <tr>
                                <td>{{ $compito->titolo }}</td>
                                <td>{{ $compito->assegnato_a }}</td>
                                <td>{{ \Carbon\Carbon::parse($compito->data_compito)->format('d/m/Y') }}</td>
                                <td>
                                    <!-- Ícono de Descrizione -->
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#descModal{{ $compito->id }}" class="text-info mx-2">
                                        <i class="fas fa-info-circle"></i>
                                    </a>

                                    <a href="{{ route('compiti.edit', $compito) }}" class="text-secondary mx-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('compiti.toggle', $compito) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="border-0 bg-transparent text-success">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('compiti.destroy', $compito) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="border-0 bg-transparent text-danger" onclick="return confirm('Eliminare compito?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Descrizione -->
                            <div class="modal fade" id="descModal{{ $compito->id }}" tabindex="-1" aria-labelledby="descModalLabel{{ $compito->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="descModalLabel{{ $compito->id }}">Descrizione Compito</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $compito->descrizione ?? 'Nessuna descrizione disponibile.' }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @endforeach
                        </tbody>
                    </table>
                </div>

                <h6 class="text-success mt-4">✅ Compiti Completati</h6>
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-4 text-center">
                        <thead>
                            <tr>
                                <th>Titolo</th>
                                <th>Assegnato A</th>
                                <th>Data</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compiti_completati as $compito)
                            <tr>
                                <td><s>{{ $compito->titolo }}</s></td>
                                <td><s>{{ $compito->assegnato_a }}</s></td>
                                <td><s>{{ \Carbon\Carbon::parse($compito->data_compito)->format('d/m/Y') }}</s></td>
                                <td>
                                    <!-- Ícono de Descrizione -->
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#descModal{{ $compito->id }}" class="text-info mx-2">
                                        <i class="fas fa-info-circle"></i>
                                    </a>

                                    <form action="{{ route('compiti.toggle', $compito) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="border-0 bg-transparent text-warning">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('compiti.destroy', $compito) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="border-0 bg-transparent text-danger" onclick="return confirm('Eliminare compito?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Descrizione -->
                            <div class="modal fade" id="descModal{{ $compito->id }}" tabindex="-1" aria-labelledby="descModalLabel{{ $compito->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="descModalLabel{{ $compito->id }}">Descrizione Compito</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $compito->descrizione ?? 'Nessuna descrizione disponibile.' }}
                                        </div>
                                        <div class="modal-footer">
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

<script>
    document.getElementById('search').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    document.getElementById('filter-date').addEventListener('change', function() {
        let selectedDate = this.value;
        document.querySelectorAll('tbody tr').forEach(row => {
            let dateText = row.cells[2]?.textContent.trim();
            let formattedDate = dateText.split('/').reverse().join('-');
            row.style.display = formattedDate === selectedDate || !selectedDate ? '' : 'none';
        });
    });
</script>

@endsection

