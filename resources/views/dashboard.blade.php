@extends('layouts.user_type.auth')

@section('content')

<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Statistiche dei Servizi</h6>
        </div>
        <div class="card-body px-4 pt-0 pb-4">
          <div class="row">
            <div class="col-md-4">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Totale dei Servizi</h5>
                  <p class="card-text">{{ $totalServizi }}</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Spesa Totale (€)</h5>
                  <p class="card-text">€{{ number_format($totaleSpesa, 2) }}</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Servizi per Tipo</h5>
                  <ul class="list-group">
                    @foreach($serviziPerTipo as $tipo => $totale)
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $tipo }}
                        <span class="badge bg-primary rounded-pill">{{ $totale }}</span>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Qui puoi aggiungere nuovo contenuto se necessario -->
    </div>
  </div>
</div>

@endsection