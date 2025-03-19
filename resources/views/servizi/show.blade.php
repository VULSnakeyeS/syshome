@extends('layouts.user_type.auth')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <div class="container mt-4">
        <h1>Dettagli del Servizio</h1>

        <div class="card">
            <div class="card-body">
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

                <a href="{{ route('servizi.index') }}" class="btn btn-secondary mt-3">Torna alla Lista</a>
            </div>
        </div>
    </div>
</main>
@endsection
