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
                <h5 class="mb-0">💰 Spese Totali</h5>
            </div>
            <div class="card-body px-4 pt-0 pb-2">
                
                <!-- ✅ Filtro por Animal y Fechas -->
                <form method="GET" action="{{ route('speseanimali.index', ['animale' => request('animale_id') ?? 'all']) }}" class="row mb-3">
                    <div class="col-md-3">
                        <label>Animale</label>
                        <select name="animale_id" class="form-control">
                            <option value="all">Tutti</option>
                            @foreach($animali as $animale)
                                <option value="{{ $animale->id }}" {{ request('animale_id') == $animale->id ? 'selected' : '' }}>
                                    {{ $animale->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Data Inizio</label>
                        <input type="date" name="data_inizio" value="{{ request('data_inizio') }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Data Fine</label>
                        <input type="date" name="data_fine" value="{{ request('data_fine') }}" class="form-control">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-info w-100">Filtra</button>
                    </div>
                </form>

                <!-- ✅ Total Gastado -->
                <div class="alert alert-info text-center">
                    <h6 class="mb-0">Totale Spese: € {{ number_format($totaleSpese ?? 0, 2, ',', '.') }}</h6>
                </div>

                <!-- ✅ Tabla de Gastos -->
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-4 text-center">
                        <thead>
                            <tr>
                                <th>Animale</th>
                                <th>Data Acquisto</th>
                                <th>Prodotto</th>
                                <th>Quantità</th>
                                <th>Prezzo Unitario (€)</th>
                                <th>Importo Totale (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($spese ?? [] as $spesa)
                            <tr>
                                <td>{{ $spesa->animale->nome }}</td>
                                <td>{{ \Carbon\Carbon::parse($spesa->data_acquisto)->format('d/m/Y') }}</td>
                                <td>{{ $spesa->nome }}</td>
                                <td>{{ $spesa->quantita }} {{ $spesa->unita_misura }}</td>
                                <td>€ {{ number_format($spesa->prezzo, 2, ',', '.') }}</td>
                                <td>€ {{ number_format($spesa->prezzo * $spesa->quantita, 2, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">Nessuna spesa registrata.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- ✅ Paginación -->
                <div class="d-flex justify-content-center">
                    {{ $spese->links() ?? '' }}
                </div>

            </div>
        </div>
    </div>
</div>

@endsection





