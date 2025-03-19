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
                <h5 class="mb-0">Visite di {{ $animale->nome }}</h5> <!-- 🔹 Cambio del texto -->
                <a href="{{ route('visite.create', $animale->id) }}" class="btn bg-gradient-primary btn-sm mb-0">+ Nuova Visita</a>
            </div>
            <div class="card-body px-4 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-4 text-center">
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
                            @foreach($visite as $visita)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($visita->data_visita)->format('d/m/Y') }}</td>
                                <td>{{ $visita->tipo }}</td>
                                <td>{{ $visita->descrizione ?? 'Nessuna descrizione' }}</td>
                                <td>
                                    @if($visita->documento)
                                        <a href="{{ asset('storage/' . $visita->documento) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-file-pdf fa-lg"></i>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
