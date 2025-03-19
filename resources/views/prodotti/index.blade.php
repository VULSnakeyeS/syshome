@extends('layouts.user_type.auth')

@section('content')

@if(session('success'))
<div class="alert alert-success mx-4" id="success-alert">
    <span class="text-white">{{ session('success') }}</span>
</div>

<script>
    setTimeout(function() {
        document.getElementById('success-alert').style.display = 'none';
    }, 3000);
</script>
@endif

@if(session('error'))
<div class="alert alert-danger mx-4" id="error-alert">
    <span class="text-white">{{ session('error') }}</span>
</div>

<script>
    setTimeout(function() {
        document.getElementById('error-alert').style.display = 'none';
    }, 5000);
</script>
@endif

<!-- ✅ Botón para Bring! (productos con bajo stock) - VERSIÓN PARA FORM POST -->
@if(isset($bringData) && $bringData['has_products'])
<div class="alert alert-warning mx-4 d-flex justify-content-between align-items-center" role="alert">
    <div>
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Attenzione!</strong> Ci sono <strong>{{ $bringData['count'] }}</strong> prodotti sotto la scorta minima.
    </div>
    <form action="{{ route('prodotti.add-to-shopping') }}" method="POST">
        @csrf
        <input type="hidden" name="products" value="{{ json_encode($bringData['products']) }}">
        <button type="submit" class="btn btn-sm btn-warning">
            <i class="fas fa-shopping-cart me-1"></i> Aggiungi alla Lista
        </button>
    </form>
</div>
@endif

<!-- ✅ BUSCADOR RESPONSIVO CORREGIDO -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm mx-3">
            <div class="card-body p-2">
                <form method="GET" action="{{ route('prodotti.index') }}" class="m-0">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text border-end-0 bg-white">
                                    <i class="fas fa-search text-primary"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" 
                                       name="search" placeholder="Cerca prodotto..." 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary w-100">
                                <span class="d-none d-md-inline">Cerca</span>
                                <i class="fas fa-search d-inline d-md-none"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Listado de productos con tabla optimizada -->
<div class="row">
    <div class="col-12">
        <div class="card mb-4 mx-3">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Gestione Prodotti</h5>
                <div>
                    <a href="#" onclick="openScanner()" class="text-success mx-1 mx-md-2 fs-4" title="Scannerizza Codice">
                        <i class="fas fa-barcode"></i>
                    </a>
                    <a href="{{ route('prodotti.create') }}" class="text-primary mx-1 mx-md-2 fs-4" title="Nuovo Prodotto">
                        <i class="fas fa-plus-circle"></i>
                    </a>
                </div>
            </div>
            <div class="card-body px-2 px-md-4 pt-0 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0" style="min-width: 650px;">
                        <thead>
                            <tr>
                                <th class="text-center">Immagine</th>
                                <th class="text-start">Nome</th>
                                <th class="text-center">Marca</th>
                                <th class="text-center">Categoria</th>
                                <th class="text-center">Quantità</th>
                                <th class="text-center">Ubicazione</th>
                                <th class="text-center">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prodotti as $prodotto)
                            <tr id="prodotto-{{ $prodotto->id }}" class="{{ isset($prodotto->minimo_scorta) && $prodotto->quantita <= $prodotto->minimo_scorta && $prodotto->minimo_scorta > 0 ? 'table-warning' : '' }}">
                                <td class="text-center">
                                    <a href="#" onclick="showImage('{{ str_contains($prodotto->immagine, 'http') ? $prodotto->immagine : asset('storage/' . $prodotto->immagine) }}')">
                                        <img src="{{ str_contains($prodotto->immagine, 'http') ? $prodotto->immagine : asset('storage/' . $prodotto->immagine) }}" class="avatar avatar-sm rounded">
                                    </a>
                                </td>
                                <td class="text-start">{{ $prodotto->nome }}</td>
                                <!-- CORRECCIÓN PARA LA MARCA -->
                                <td class="text-center">{{ $prodotto->marca ? $prodotto->marca : 'N/D' }}</td>
                                <td class="text-center">{{ $prodotto->categoria }}</td>
                                <td id="quantita-{{ $prodotto->id }}" class="text-center {{ isset($prodotto->minimo_scorta) && $prodotto->quantita <= $prodotto->minimo_scorta && $prodotto->minimo_scorta > 0 ? 'text-danger fw-bold' : '' }}">
                                    {{ $prodotto->quantita }}
                                    @if(isset($prodotto->minimo_scorta) && $prodotto->minimo_scorta > 0)
                                        <small class="d-block text-muted">(Min: {{ $prodotto->minimo_scorta }})</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ ucfirst($prodotto->ubicazione) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('prodotti.edit', $prodotto->id) }}" class="text-secondary mx-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="text-danger mx-1" onclick="confirmDelete({{ $prodotto->id }})">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- ✅ Paginación -->
                <div class="text-center mt-3">
                    <p class="text-muted">
                        Mostrando {{ $prodotti->firstItem() ?? 0 }} a {{ $prodotti->lastItem() ?? 0 }} di {{ $prodotti->total() }} risultati
                    </p>
                    {{ $prodotti->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formularios de eliminación -->
@foreach($prodotti as $prodotto)
<form id="delete-form-{{ $prodotto->id }}" action="{{ route('prodotti.destroy', $prodotto->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endforeach

<!-- ✅ Modal Scanner -->
<div class="modal fade" id="scannerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scansiona il Codice a Barre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="stopScanner()"></button>
            </div>
            <div class="modal-body text-center">
                <div id="scanner-container" class="d-flex justify-content-center">
                    <video id="interactive" class="viewport w-100 h-auto" autoplay playsinline></video>
                </div>
                <div id="scanner-status" class="mt-2 text-muted">Pronto per la scansione...</div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Modal para modificar cantidad con botones +/- -->
<div class="modal fade" id="quantityModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifica Quantità</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="product-info" class="text-center mb-4">
                    <h4 id="product-name" class="mb-1"></h4>
                    <p id="product-marca" class="text-muted mb-3"></p>
                    <div class="d-flex justify-content-center align-items-center">
                        <span class="me-2">Quantità attuale:</span>
                        <span id="current-quantity" class="fw-bold fs-4">0</span>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="quantity-control d-flex justify-content-center align-items-center">
                            <button type="button" class="btn btn-outline-primary" onclick="decrementQuantity()">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="quantity-amount" class="form-control mx-3 text-center" 
                                   style="max-width: 80px" value="1" min="1">
                            <button type="button" class="btn btn-outline-primary" onclick="incrementQuantity()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="updateProductQuantity('increase')">
                    <i class="fas fa-plus-circle me-1"></i> Aggiungi
                </button>
                <button type="button" class="btn btn-warning" onclick="updateProductQuantity('decrease')">
                    <i class="fas fa-minus-circle me-1"></i> Rimuovi
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annulla</button>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Modal Imagen -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Immagine del Prodotto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- ✅ Modal para productos con bajo stock - VERSIÓN ACTUALIZADA -->
@if(isset($bringData) && $bringData['has_products'])
<div class="modal fade" id="lowStockModal" tabindex="-1" aria-labelledby="lowStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="lowStockModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Prodotti con basso stock
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>I seguenti prodotti sono sotto la scorta minima:</p>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Marca</th>
                                <th>Quantità</th>
                                <th>Minimo Scorta</th>
                                <th>Ubicazione</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bringData['products'] as $product)
                            <tr>
                                <td>{{ $product->nome }}</td>
                                <td>{{ $product->marca ?: 'N/D' }}</td>
                                <td class="text-danger fw-bold">{{ $product->quantita }}</td>
                                <td>{{ $product->minimo_scorta }}</td>
                                <td>{{ ucfirst($product->ubicazione) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                <form action="{{ route('bring.redirect') }}" method="POST">
                    @csrf
                    <input type="hidden" name="products" value="{{ json_encode($bringData['products']) }}">
                    <input type="hidden" name="list_uuid" value="e3b05008-8344-4978-9b4d-79d8fb788c75">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-shopping-cart me-1"></i> Invia a Bring!
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- ✅ Librería ZXing.js -->
<script src="https://unpkg.com/@zxing/browser@latest"></script>
<script src="https://unpkg.com/@zxing/library@latest"></script>

<script>
    let codeReader;
    let videoStream;
    let currentBarcode = "";
    let scannerStatus = document.getElementById('scanner-status');
    let currentProductId = null;

    // Mostrar modal de productos con bajo stock al cargar
    document.addEventListener('DOMContentLoaded', function() {
        @if(isset($bringData) && $bringData['has_products'])
        // Comprobar si ya se ha mostrado el modal en esta sesión
        if (!sessionStorage.getItem('lowStockModalShown')) {
            setTimeout(() => {
                const lowStockModal = new bootstrap.Modal(document.getElementById('lowStockModal'));
                lowStockModal.show();
                // Marcar como mostrado en esta sesión
                sessionStorage.setItem('lowStockModalShown', 'true');
            }, 1000);
        }
        @endif
    });

    function openScanner() {
        let scannerModal = new bootstrap.Modal(document.getElementById('scannerModal'));
        scannerModal.show();
    }

    document.getElementById('scannerModal').addEventListener('shown.bs.modal', function () {
        setTimeout(() => {
            try {
                if (typeof ZXing === "undefined") {
                    throw new Error("La librería ZXing no se ha cargado correctamente.");
                }
                startScanner();
            } catch (error) {
                console.error("Error al iniciar el escáner:", error);
                alert("Error al iniciar el escáner: " + error.message);
            }
        }, 500);
    });

    function startScanner() {
        if (!codeReader) {
            codeReader = new ZXing.BrowserMultiFormatReader();
        }

        if (scannerStatus) {
            scannerStatus.textContent = "Inizializzazione della fotocamera...";
        }

        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
            .then(stream => {
                videoStream = stream;
                document.getElementById("interactive").srcObject = stream;
                
                if (scannerStatus) {
                    scannerStatus.textContent = "Fotocamera pronta. Punta al codice a barre...";
                }

                codeReader.decodeFromVideoDevice(undefined, 'interactive', (result, err) => {
                    if (result) {
                        if (scannerStatus) {
                            scannerStatus.textContent = "Codice rilevato: " + result.text;
                        }
                        stopScanner();
                        currentBarcode = result.text;
                        verificaProdotto(result.text);
                    }
                    if (err && !(err instanceof ZXing.NotFoundException)) {
                        console.error("Errore durante la scansione:", err);
                    }
                });
            })
            .catch(err => {
                console.error("Errore acceso camera:", err);
                alert("Errore acceso camera: " + err.message);
            });
    }

    function stopScanner() {
        try {
            if (codeReader) {
                codeReader.reset();
            }

            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
            }

            let scannerModalEl = document.getElementById('scannerModal');
            let modal = bootstrap.Modal.getOrCreateInstance(scannerModalEl);
            if (modal) {
                modal.hide();
            }
        } catch (error) {
            console.error("Error al detener el escáner:", error);
        }
    }

    function verificaProdotto(barcode) {
        try {
            console.log("Verificando código de barras:", barcode);
            
            fetch(`/prodotti/scan`, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ barcode: barcode })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Risposta del server non valida. Status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log("Datos recibidos del servidor:", data);
                
                // Si el producto existe en la base de datos
                if (data.exists) {
                    showQuantityModal(data);
                } 
                // Si se encontró en OpenFoodFacts
                else if (data.found) {
                    const url = `/prodotti/create?barcode=${encodeURIComponent(data.barcode)}&nome=${encodeURIComponent(data.nome)}&marca=${encodeURIComponent(data.marca)}&categoria=${encodeURIComponent(data.categoria)}&immagine=${encodeURIComponent(data.immagine)}`;
                    console.log("Redirigiendo a:", url);
                    window.location.href = url;
                } 
                // Si no se encontró en ningún lado
                else {
                    alert("Prodotto non trovato. Vuoi aggiungerlo manualmente?");
                    const url = `/prodotti/create?barcode=${encodeURIComponent(barcode)}`;
                    console.log("Redirigiendo a:", url);
                    window.location.href = url;
                }
            })
            .catch(error => {
                console.error('Errore durante la verifica del prodotto:', error);
                alert('Errore durante la verifica del prodotto: ' + error.message);
            });
        } catch (error) {
            console.error('Errore nella funzione verificaProdotto:', error);
            alert('Errore nella funzione verificaProdotto: ' + error.message);
        }
    }

    function incrementQuantity() {
        let quantityInput = document.getElementById('quantity-amount');
        quantityInput.value = parseInt(quantityInput.value || 1) + 1;
    }
    
    function decrementQuantity() {
        let quantityInput = document.getElementById('quantity-amount');
        let currentValue = parseInt(quantityInput.value || 2);
        quantityInput.value = Math.max(1, currentValue - 1);
    }

    function showQuantityModal(data) {
        // Guardar el ID del producto actual
        currentProductId = data.prodotto_id;
        
        // Actualizar la información en el modal
        document.getElementById('product-name').textContent = data.nome;
        document.getElementById('product-marca').textContent = data.marca || 'N/D';
        document.getElementById('current-quantity').textContent = data.quantita;
        document.getElementById('quantity-amount').value = 1;
        
        // Mostrar el modal
        let quantityModal = new bootstrap.Modal(document.getElementById('quantityModal'));
        quantityModal.show();
    }

    function updateProductQuantity(action) {
        if (!currentProductId) {
            console.error("Error: No se ha seleccionado un producto");
            return;
        }
        
        const amount = parseInt(document.getElementById('quantity-amount').value);
        if (isNaN(amount) || amount <= 0) {
            alert("Per favore, inserisci una quantità valida maggiore di zero");
            return;
        }
        
        // Enviar solicitud para actualizar la cantidad
        fetch(`/prodotti/update-quantity`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                prodotto_id: currentProductId,
                action: action,
                amount: amount
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Risposta del server non valida. Status: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Actualizar la cantidad mostrada en la tabla si el producto está visible
                const quantityCell = document.getElementById(`quantita-${currentProductId}`);
                if (quantityCell) {
                    quantityCell.textContent = data.new_quantity;
                }
                
                // Cerrar el modal
                let quantityModalEl = document.getElementById('quantityModal');
                let modal = bootstrap.Modal.getInstance(quantityModalEl);
                if (modal) {
                    modal.hide();
                }

                // Si el producto ahora está en bajo stock y hay información de Bring!
                if (data.is_low_stock && data.bring_data && data.bring_data.has_products) {
                    // Si no existe la alerta de bajo stock, crearla
                    showLowStockAlert(data.bring_data);
                }
                
                // Mostrar mensaje de éxito
                alert(`Quantità aggiornata con successo! (${data.old_quantity} → ${data.new_quantity})`);
                
                // Recargar la página para actualizar todo
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                alert('Errore: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Errore durante l\'aggiornamento della quantità:', error);
            alert('Errore durante l\'aggiornamento: ' + error.message);
        });
    }

    function showLowStockAlert(bringData) {
        // Comprobar si ya existe una alerta
        const existingAlert = document.querySelector('.low-stock-alert');
        
        if (existingAlert) {
            // Actualizar la existente
            existingAlert.querySelector('.product-count').textContent = bringData.count;
            existingAlert.querySelector('.bring-link').href = bringData.link;
        } else {
            // Crear nueva alerta
            const alert = document.createElement('div');
            alert.className = 'alert alert-warning mx-4 d-flex justify-content-between align-items-center low-stock-alert';
            alert.role = 'alert';
            alert.innerHTML = `
                <div>
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attenzione!</strong> Ci sono <strong class="product-count">${bringData.count}</strong> prodotti sotto la scorta minima.
                </div>
                <form action="{{ route('bring.redirect') }}" method="POST">
                    @csrf
                    <input type="hidden" name="products" value='${JSON.stringify(bringData.products)}'>
                    <input type="hidden" name="list_uuid" value="e3b05008-8344-4978-9b4d-79d8fb788c75">
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fas fa-shopping-cart me-1"></i> Invia a Bring!
                    </button>
                </form>
            `;
            
            // Insertar al principio de la página
            const container = document.querySelector('.container-fluid') || document.body;
            container.insertBefore(alert, container.firstChild);
        }
    }

    function showImage(imageUrl) {
        try {
            let imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            document.getElementById('modalImage').src = imageUrl;
            imageModal.show();
        } catch (error) {
            console.error("Error al mostrar la imagen:", error);
        }
    }

    function confirmDelete(productId) {
        if (confirm("Sei sicuro di voler eliminare questo prodotto?")) {
            // Mostrar mensaje de carga
            console.log("Eliminando producto ID:", productId);
            
            // Enviar el formulario de eliminación
            document.getElementById(`delete-form-${productId}`).submit();
        }
    }
</script>

@endsection