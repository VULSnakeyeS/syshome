@extends('layouts.app')

@section('auth')
    @include('layouts.navbars.auth.sidebar')

    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
        @include('layouts.navbars.auth.nav')
        <div class="container-fluid py-4">
            @yield('content')
            @include('layouts.footers.auth.footer')
        </div>
    </main>

    @include('components.fixed-plugin')

    <!-- ✅ QuaggaJS para el escaneo de código de barras -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

@endsection

