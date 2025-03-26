@extends('layouts.user_type.auth')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Aggiungi Nuova Opzione al Dropdown</h6>
                    </div>
                    <div class="card-body px-4 pt-0 pb-4">
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('dropdown-options.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="dropdown_name" class="form-label">Nome del Dropdown</label>
                                <input type="text" name="dropdown_name" class="form-control" placeholder="Inserisci il nome del dropdown" required>
                            </div>
                            <div class="mb-3">
                                <label for="option_value" class="form-label">Valore dell'Opzione</label>
                                <input type="text" name="option_value" class="form-control" placeholder="Inserisci il valore dell'opzione" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">Salva</button>
                                <a href="{{ route('dropdown-options.index') }}" class="btn btn-secondary">Annulla</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection