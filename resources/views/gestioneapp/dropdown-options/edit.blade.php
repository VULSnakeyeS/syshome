@extends('layouts.user_type.auth')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Modifica Opzione del Dropdown</h6>
                    </div>
                    <div class="card-body px-4 pt-0 pb-4">
                        <form action="{{ route('dropdown-options.update', $dropdownOption->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="dropdown_name" class="form-label">Nome del Dropdown</label>
                                <input type="text" name="dropdown_name" class="form-control" value="{{ $dropdownOption->dropdown_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="option_value" class="form-label">Valore dell'Opzione</label>
                                <input type="text" name="option_value" class="form-control" value="{{ $dropdownOption->option_value }}" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">Salva Modifiche</button>
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