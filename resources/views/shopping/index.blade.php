@extends('layouts.user_type.auth')

@section('content')
<style>
    /* Estilos personalizados para la lista de compras al estilo Bring! */
    .shopping-container {
        padding-bottom: 80px; /* Espacio para el botón flotante y la barra de búsqueda */
    }
    
    .shopping-item {
        border-radius: 12px;
        border: none;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    
    .shopping-item:active {
        transform: scale(0.97);
    }
    
    .item-image {
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    
    .item-image img {
        max-height: 100px;
        max-width: 100%;
        object-fit: contain;
    }
    
    .item-needed {
        background-color: rgba(220, 53, 69, 0.1);
        border-left: 4px solid #dc3545;
    }
    
    .item-purchased {
        background-color: rgba(25, 135, 84, 0.1);
        border-left: 4px solid #198754;
    }
    
    .item-name {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0;
        line-height: 1.2;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .item-marca {
        font-size: 0.75rem;
        color: #6c757d;
    }
    
    .quantity-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #FFF;
        color: #000;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .search-bar {
        position: fixed;
        bottom: 70px;
        left: 0;
        right: 0;
        z-index: 999;
        background: white;
        padding: 10px 20px;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        border-radius: 30px;
        margin: 0 auto;
        width: 90%;
        max-width: 500px;
    }
    
    .search-bar input {
        border-radius: 30px;
        border: 1px solid #e9ecef;
        padding-left: 40px;
        background-color: #f8f9fa;
    }
    
    .search-bar .search-icon {
        position: absolute;
        left: 35px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    
    .floating-add-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        z-index: 1000;
    }
    
    .category-title {
        font-weight: bold;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 10px 15px;
        color: #495057;
        border-bottom: 1px solid #e9ecef;
    }
    
    /* Para dispositivos móviles, hacemos los elementos más grandes */
    @media (max-width: 576px) {
        .shopping-items .col-6 {
            width: 50%; /* 2 elementos por fila en móviles */
        }
        
        .item-image {
            height: 100px;
        }
    }
</style>

<div class="container-fluid py-4 shopping-container">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
        <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
        <span class="alert-text">{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Secciones de la lista (Necesarios y Comprados) -->
    <div class="row">
        <div class="col-12 px-4">
            <!-- Título y contador -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i> Lista della Spesa</h5>
                <span class="badge bg-primary">{{ $totalItems - $purchasedItems }} / {{ $totalItems }}</span>
            </div>
            
            <!-- Lista de productos a comprar -->
            @if($itemsByCategory->count() == 0)
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart text-muted mb-3" style="font-size: 3rem;"></i>
                    <h6 class="text-muted">La lista della spesa è vuota</h6>
                    <p class="text-sm text-muted">Aggiungi prodotti alla lista usando il pulsante "+" in basso a destra.</p>
                </div>
            @else
                <!-- Sección: Productos a comprar -->
                <div class="mb-4">
                    <div class="category-title">
                        <i class="fas fa-list-alt text-danger me-2"></i> Da comprare
                    </div>
                    
                    @foreach($itemsByCategory as $categoria => $items)
                        @php
                            $neededItems = $items->where('purchased', false);
                            if($neededItems->count() == 0) continue;
                        @endphp
                        
                        <div class="category-section mt-2 mb-4">
                            <h6 class="ps-3 mb-2 text-muted">{{ ucfirst($categoria) }}</h6>
                            
                            <div class="shopping-items row g-3">
                                @foreach($neededItems as $item)
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                    <div class="shopping-item card position-relative item-needed" 
                                         onclick="toggleItemStatus({{ $item->id }}, {{ $item->purchased ? 'true' : 'false' }})">
                                        <div class="shopping-item-content text-center p-3">
                                            <!-- Cantidad -->
                                            @if($item->quantita > 1)
                                                <div class="quantity-badge">{{ $item->quantita }}</div>
                                            @endif
                                            
                                            <!-- Imagen o icono -->
                                            <div class="item-image">
                                                @if($item->immagine)
                                                <img src="{{ str_contains($item->immagine, 'http') ? $item->immagine : asset('storage/' . $item->immagine) }}" alt="{{ $item->nome }}">
                                                @else
                                                <i class="fas fa-shopping-basket text-danger" style="font-size: 2.5rem;"></i>
                                                @endif
                                            </div>
                                            
                                            <!-- Información del producto -->
                                            <h6 class="item-name">{{ $item->nome }}</h6>
                                            @if($item->marca)
                                                <p class="item-marca">{{ $item->marca }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Sección: Productos comprados -->
                @if($purchasedItems > 0)
                    <div class="mt-4">
                        <div class="category-title d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-check-circle text-success me-2"></i> Acquistati
                            </div>
                            <form action="{{ route('shopping.clear-purchased') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-trash-alt"></i> Pulisci
                                </button>
                            </form>
                        </div>
                        
                        <div class="shopping-items row g-3 mt-2">
                            @foreach($itemsByCategory as $categoria => $items)
                                @foreach($items->where('purchased', true) as $item)
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                    <div class="shopping-item card position-relative item-purchased"
                                         onclick="toggleItemStatus({{ $item->id }}, {{ $item->purchased ? 'true' : 'false' }})">
                                        <div class="shopping-item-content text-center p-3">
                                            <!-- Cantidad -->
                                            @if($item->quantita > 1)
                                                <div class="quantity-badge">{{ $item->quantita }}</div>
                                            @endif
                                            
                                            <!-- Imagen o icono -->
                                            <div class="item-image">
                                                @if($item->immagine)
                                                <img src="{{ str_contains($item->immagine, 'http') ? $item->immagine : asset('storage/' . $item->immagine) }}" alt="{{ $item->nome }}">
                                                @else
                                                <i class="fas fa-shopping-basket text-success" style="font-size: 2.5rem;"></i>
                                                @endif
                                            </div>
                                            
                                            <!-- Información del producto -->
                                            <h6 class="item-name">{{ $item->nome }}</h6>
                                            @if($item->marca)
                                                <p class="item-marca">{{ $item->marca }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Barra de búsqueda "Mi serve..." como en Bring! -->
    <div class="search-bar">
        <div class="position-relative">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="form-control" id="quickSearch" placeholder="Mi serve..." oninput="quickSearch(this.value)">
        </div>
    </div>
    
    <!-- Botón flotante verde para añadir productos -->
    <a href="#" class="btn btn-success floating-add-btn" data-bs-toggle="modal" data-bs-target="#addItemModal">
        <i class="fas fa-plus" style="font-size: 1.5rem;"></i>
    </a>
</div>

<!-- Modal para añadir un nuevo artículo manualmente -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Aggiungi Articolo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('shopping.add-manual') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome Prodotto</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="marca" class="form-label">Marca (Opzionale)</label>
                        <input type="text" class="form-control" id="marca" name="marca">
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoria</label>
                        <select class="form-control" id="categoria" name="categoria" required>
                            <option value="">Seleziona categoria...</option>
                            <option value="frutta e verdura">Frutta e Verdura</option>
                            <option value="carne e pesce">Carne e Pesce</option>
                            <option value="latticini">Latticini</option>
                            <option value="pane e cereali">Pane e Cereali</option>
                            <option value="surgelati">Surgelati</option>
                            <option value="bevande">Bevande</option>
                            <option value="snack e dolci">Snack e Dolci</option>
                            <option value="prodotti per la casa">Prodotti per la Casa</option>
                            <option value="cura personale">Cura Personale</option>
                            <option value="altro">Altro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantita" class="form-label">Quantità</label>
                        <input type="number" class="form-control" id="quantita" name="quantita" min="1" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="immagine" class="form-label">URL Immagine (Opzionale)</label>
                        <input type="text" class="form-control" id="immagine" name="immagine">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Aggiungi alla Lista</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para resultados de búsqueda rápida -->
<div class="modal fade" id="quickSearchModal" tabindex="-1" aria-labelledby="quickSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickSearchModalLabel">Risultati di Ricerca</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="searchResults" class="list-group">
                    <!-- Los resultados de la búsqueda se insertarán aquí dinámicamente -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para acciones sobre un producto -->
<div class="modal fade" id="itemActionModal" tabindex="-1" aria-labelledby="itemActionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemActionModalLabel">Azioni Prodotto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="itemActionContent" class="text-center">
                    <div class="item-image d-flex align-items-center justify-content-center mb-3">
                        <img id="itemActionImage" src="" alt="" style="max-height: 120px; max-width: 100%;">
                    </div>
                    <h5 id="itemActionName" class="mb-1"></h5>
                    <p id="itemActionBrand" class="text-muted mb-3"></p>
                    
                    <div class="d-flex justify-content-center align-items-center my-3">
                        <button class="btn btn-sm btn-outline-primary" id="btnDecreaseQuantity">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="mx-3 fw-bold" id="itemActionQuantity">1</span>
                        <button class="btn btn-sm btn-outline-primary" id="btnIncreaseQuantity">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    
                    <div class="row g-2 mt-3">
                        <div class="col-6">
                            <button id="btnTogglePurchased" class="btn btn-success w-100">
                                <i class="fas fa-check me-1"></i> Acquistato
                            </button>
                        </div>
                        <div class="col-6">
                            <button id="btnRemoveItem" class="btn btn-danger w-100">
                                <i class="fas fa-trash-alt me-1"></i> Rimuovi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Marcar/desmarcar un producto como comprado
    function toggleItemStatus(itemId, isPurchased) {
        const action = isPurchased ? 'unpurchase' : 'purchase';
        
        fetch(`/shopping-list/${itemId}/${action}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Errore durante l\'aggiornamento dello stato');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Errore: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Si è verificato un errore durante l\'aggiornamento dello stato');
        });
    }

    // Función para búsqueda rápida de productos
    function quickSearch(query) {
        if (!query || query.length < 2) {
            // No hacer nada si la consulta es muy corta
            return;
        }
        
        fetch(`/shopping-list/search?q=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                // Mostrar resultados en un modal
                const searchResults = document.getElementById('searchResults');
                searchResults.innerHTML = '';
                
                data.forEach(item => {
                    const resultItem = document.createElement('a');
                    resultItem.href = '#';
                    resultItem.className = 'list-group-item list-group-item-action d-flex align-items-center';
                    resultItem.onclick = function(e) {
                        e.preventDefault();
                        addToShoppingList(item.id, item.nome);
                    };
                    
                    let imageHtml = '';
                    if (item.immagine) {
                        const imgSrc = item.immagine.includes('http') ? item.immagine : `/storage/${item.immagine}`;
                        imageHtml = `<img src="${imgSrc}" alt="${item.nome}" style="width: 40px; height: 40px; object-fit: contain; margin-right: 15px;">`;
                    } else {
                        imageHtml = `<div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-shopping-basket text-muted"></i>
                                     </div>`;
                    }
                    
                    resultItem.innerHTML = `
                        ${imageHtml}
                        <div>
                            <div class="fw-bold">${item.nome}</div>
                            <small class="text-muted">${item.marca || 'N/D'}</small>
                        </div>
                        <div class="ms-auto">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    `;
                    
                    searchResults.appendChild(resultItem);
                });
                
                // Mostrar el modal
                const quickSearchModal = new bootstrap.Modal(document.getElementById('quickSearchModal'));
                quickSearchModal.show();
            }
        })
        .catch(error => {
            console.error('Error en la búsqueda:', error);
        });
    }

    // Añadir un producto desde la búsqueda a la lista de compras
    function addToShoppingList(productId, productName) {
        fetch('/shopping-list/add-from-search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cerrar el modal de búsqueda
                const quickSearchModal = bootstrap.Modal.getInstance(document.getElementById('quickSearchModal'));
                quickSearchModal.hide();
                
                // Recargar la página para mostrar el nuevo elemento
                location.reload();
            } else {
                alert('Errore: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Errore durante l\'aggiunta del prodotto:', error);
            alert('Si è verificato un errore durante l\'aggiunta del prodotto alla lista');
        });
    }
</script>
@endsection