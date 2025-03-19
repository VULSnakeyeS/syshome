<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-itunes-app" content="app-id=580669177">
    <title>Reindirizzamento a Bring!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #f7b500; /* Color Bring! */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .btn {
            background-color: #f7b500; /* Color Bring! */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            margin: 10px;
            text-decoration: none;
            display: inline-block;
        }
        .product-list {
            text-align: left;
            margin: 20px auto;
            max-width: 400px;
        }
        .product-item {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        #options {
            margin-top: 20px;
        }
        .option-description {
            font-size: 0.9em;
            margin-top: 5px;
            color: #666;
        }
        .copy-link {
            margin-top: 20px;
            display: none;
        }
        .copy-box {
            display: flex;
            margin: 10px auto;
            max-width: 500px;
        }
        .copy-input {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
            font-size: 14px;
        }
        .copy-button {
            background: #555;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Invio prodotti a Bring!</h2>
        <p>Stiamo preparando <strong>{{ $total }}</strong> prodotti per la tua lista "Casa" in Bring!</p>
        
        <div class="product-list">
            <h4>Prodotti da aggiungere:</h4>
            @foreach($products as $product)
            <div class="product-item">
                <strong>{{ $product->nome }}</strong> 
                @if(!empty($product->marca))
                <span>({{ $product->marca }})</span>
                @endif
            </div>
            @endforeach
        </div>
        
        <div class="loader" id="main-loader"></div>
        
        <div id="options" style="display: none;">
            <p>Seleziona come vuoi aprire Bring!:</p>
            
            <div>
                <a href="#" id="direct-link" class="btn">
                    <i class="fas fa-external-link-alt"></i> Apri Bring!
                </a>
                <div class="option-description">
                    Tentativo di apertura diretta dell'app Bring!
                </div>
            </div>
            
            <div style="margin-top: 20px;">
                <a href="#" id="backup-link" class="btn" style="background-color: #4CAF50;">
                    <i class="fas fa-list"></i> Apri in formato semplice
                </a>
                <div class="option-description">
                    Usa questo se il primo metodo non funziona
                </div>
            </div>
            
            <div class="copy-link">
                <p>Se nessuna opzione funziona, copia questo link e aprilo nel browser del tuo dispositivo:</p>
                <div class="copy-box">
                    <input type="text" id="copy-input" class="copy-input" readonly>
                    <button id="copy-button" class="copy-button">Copia</button>
                </div>
                <div id="copy-message" style="margin-top: 5px; font-size: 0.9em; color: green; display: none;">
                    Link copiato!
                </div>
            </div>
        </div>
        
        <div id="error-message" style="color: red; margin-top: 20px; display: none;">
            Si è verificato un problema durante il reindirizzamento. Prova una delle opzioni alternative.
        </div>
    </div>

    <script>
        // Prepare data for Bring!
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Crear el objeto de datos para Bring!
                const products = @json($products);
                const itemsToAdd = [];
                
                // Formatear correctamente los productos para Bring!
                products.forEach(product => {
                    const item = {
                        "name": product.nome
                    };
                    
                    if (product.marca && product.marca.trim() !== '') {
                        item.specification = product.marca.trim();
                    }
                    
                    itemsToAdd.push(item);
                });
                
                // UUID de la lista "Casa" - ¡Ahora tenemos el UUID correcto de la API!
                const casaListUuid = "e3b05008-8344-4978-9b4d-79d8fb788c75";
                
                // Construimos el objeto completo según el formato requerido
                const bringData = {
                    "itemsToAdd": itemsToAdd,
                    "createNewItems": true,
                    "listUuid": casaListUuid
                };
                
                // Convertir a JSON y codificar para URL
                const jsonString = JSON.stringify(bringData);
                const encodedJson = encodeURIComponent(jsonString);
                
                // URL para web (formato observado en desktop)
                const bringWebUrl = "https://web.getbring.com/app/lists?json=" + encodedJson;
                
                // URL que intenta abrir directamente la app en dispositivos móviles
                const bringAppUrl = "bring://items-import?json=" + encodedJson;
                
                // URL de respaldo para formato simple/alternativo
                const simpleUrl = `https://web.getbring.com/lists/${casaListUuid}`;
                
                console.log("URL completa:", bringWebUrl);
                console.log("URL app:", bringAppUrl);
                console.log("URL simple:", simpleUrl);
                
                // Determinar la mejor URL según el dispositivo
                const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                const primaryUrl = isMobile ? bringAppUrl : bringWebUrl;
                
                // Asignar URLs a los botones
                document.getElementById('direct-link').href = primaryUrl;
                document.getElementById('backup-link').href = simpleUrl;
                document.getElementById('copy-input').value = bringWebUrl;
                
                // Después de 1.5 segundos, mostrar las opciones
                setTimeout(() => {
                    document.getElementById('main-loader').style.display = 'none';
                    document.getElementById('options').style.display = 'block';
                    document.querySelector('.copy-link').style.display = 'block';
                    
                    // Intentar abrir Bring! automáticamente (pero solo en móviles)
                    if (isMobile) {
                        try {
                            window.location.href = primaryUrl;
                            
                            // Después de un tiempo, si seguimos aquí, mostrar un mensaje
                            setTimeout(() => {
                                document.getElementById('error-message').style.display = 'block';
                            }, 3000);
                        } catch (e) {
                            console.error("Error al intentar redirigir:", e);
                            document.getElementById('error-message').style.display = 'block';
                        }
                    }
                }, 1500);
                
                // Funcionalidad para copiar el enlace
                document.getElementById('copy-button').addEventListener('click', function() {
                    const copyInput = document.getElementById('copy-input');
                    copyInput.select();
                    copyInput.setSelectionRange(0, 99999); // Para dispositivos móviles
                    
                    try {
                        // Intentar copiar usando la API moderna
                        navigator.clipboard.writeText(copyInput.value)
                            .then(() => {
                                document.getElementById('copy-message').style.display = 'block';
                                setTimeout(() => {
                                    document.getElementById('copy-message').style.display = 'none';
                                }, 3000);
                            })
                            .catch(err => {
                                // Fallback al método tradicional si falla
                                document.execCommand('copy');
                                document.getElementById('copy-message').style.display = 'block';
                                setTimeout(() => {
                                    document.getElementById('copy-message').style.display = 'none';
                                }, 3000);
                            });
                    } catch (err) {
                        // Fallback para navegadores más antiguos
                        document.execCommand('copy');
                        document.getElementById('copy-message').style.display = 'block';
                        setTimeout(() => {
                            document.getElementById('copy-message').style.display = 'none';
                        }, 3000);
                    }
                });
                
            } catch (error) {
                console.error('Errore durante la preparazione dei dati:', error);
                document.getElementById('main-loader').style.display = 'none';
                document.getElementById('error-message').style.display = 'block';
                document.getElementById('error-message').textContent = 'Errore: ' + error.message;
            }
        });
    </script>
</body>
</html>