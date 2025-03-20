@extends('layouts.user_type.auth')

@section('content')

<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Estadísticas de Servicios</h6>
        </div>
        <div class="card-body px-4 pt-0 pb-4">
          <div class="row">
            <div class="col-md-4">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Total de Servicios</h5>
                  <p class="card-text">{{ $totalServicios }}</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Gasto Total (€)</h5>
                  <p class="card-text">€{{ number_format($totalGasto, 2) }}</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Servicios por Tipo</h5>
                  <ul class="list-group">
                    @foreach($serviciosPorTipo as $tipo => $total)
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $tipo }}
                        <span class="badge bg-primary rounded-pill">{{ $total }}</span>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Aquí puedes añadir nuevo contenido si es necesario -->
    </div>
  </div>
</div>

@endsection