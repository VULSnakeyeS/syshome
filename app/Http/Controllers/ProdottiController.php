<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodotto;
use App\Models\ShoppingListItem; // Nuevo import para la lista de compras
use App\Services\BringIntegrationService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProdottiController extends Controller
{
    protected $bringService;

    public function __construct(BringIntegrationService $bringService = null)
    {
        // Si no se inyecta el servicio, crea una nueva instancia
        $this->bringService = $bringService ?? new BringIntegrationService();
    }

    public function index(Request $request)
    {
        $query = Prodotto::query();

        // Filtro de búsqueda si se proporciona un término
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->search . '%')
                  ->orWhere('marca', 'like', '%' . $request->search . '%')
                  ->orWhere('barcode', 'like', '%' . $request->search . '%');
            });
        }

        // Ordenar alfabéticamente y paginar
        $prodotti = $query->orderBy('nome', 'asc')->paginate(10);
        
        // Generar enlace para productos con bajo stock
        $bringData = $this->bringService->generateLowStockLink();

        return view('prodotti.index', compact('prodotti', 'bringData'));
    }

    public function scan(Request $request)
    {
        $barcode = $request->input('barcode');

        if (!$barcode) {
            return response()->json(['success' => false, 'message' => 'Codice a barre non valido.']);
        }

        // Buscar en la base de datos local
        $prodotto = Prodotto::where('barcode', $barcode)->first();

        if ($prodotto) {
            return response()->json([
                'success' => true,
                'found' => true,
                'prodotto_id' => $prodotto->id,
                'nome' => $prodotto->nome,
                'marca' => $prodotto->marca,
                'quantita' => $prodotto->quantita,
                'exists' => true // Nueva bandera para indicar que el producto ya existe
            ]);
        }

        // Si no está en la base de datos, buscar en OpenFoodFacts
        $response = Http::get("https://world.openfoodfacts.org/api/v2/product/{$barcode}.json");

        if ($response->successful() && isset($response['product'])) {
            $productData = $response['product'];
            return response()->json([
                'success' => false,
                'found' => true,
                'barcode' => $barcode,
                'nome' => $productData['product_name'] ?? '',
                'marca' => $productData['brands'] ?? '',
                'categoria' => $productData['categories_tags'][0] ?? '',
                'immagine' => $productData['image_url'] ?? ''
            ]);
        }

        return response()->json([
            'success' => false,
            'found' => false,
            'message' => 'Prodotto non trovato né nel database né su OpenFoodFacts.'
        ]);
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'prodotto_id' => 'required|integer',
            'action' => 'required|string|in:increase,decrease',
            'amount' => 'required|integer'
        ]);

        try {
            DB::beginTransaction();
            
            $prodotto = Prodotto::findOrFail($request->prodotto_id);
            $oldQuantity = $prodotto->quantita;
            
            if ($request->action == 'increase') {
                $prodotto->quantita += $request->amount;
            } else {
                // Permitimos valores negativos
                $prodotto->quantita -= $request->amount;
            }
            
            $prodotto->save();
            
            DB::commit();
            
            // Verificamos si cambió el estado de stock (por encima/debajo del mínimo)
            $wasLowStock = $oldQuantity > $prodotto->minimo_scorta;
            $isLowStock = $prodotto->quantita <= $prodotto->minimo_scorta;
            
            // Si cambió de estado a bajo stock, obtenemos info para el botón de Bring!
            $bringData = null;
            if ($wasLowStock && $isLowStock) {
                $bringData = $this->bringService->generateLowStockLink();
            }
            
            return response()->json([
                'success' => true, 
                'message' => 'Quantità aggiornata con successo',
                'old_quantity' => $oldQuantity,
                'new_quantity' => $prodotto->quantita,
                'is_low_stock' => $isLowStock,
                'bring_data' => $bringData
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar cantidad', [
                'error' => $e->getMessage(),
                'prodotto_id' => $request->prodotto_id
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Errore durante l\'aggiornamento: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request)
    {
        return view('prodotti.create', [
            'barcode' => $request->barcode,
            'nome' => $request->nome,
            'marca' => $request->marca,
            'categoria' => $request->categoria,
            'immagine' => $request->immagine,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'marca' => 'nullable|string|max:255',
            'categoria' => 'required|string|max:255',
            'quantita' => 'required|integer',
            'ubicazione' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255|unique:prodotti,barcode',
            'immagine' => 'nullable|string|max:500',
            'minimo_scorta' => 'nullable|integer|min:0',
            'note' => 'nullable|string|max:500'
        ]);

        $prodotto = Prodotto::create($request->all());

        // Verificar si el producto ya está por debajo del stock mínimo al crearlo
        if ($prodotto->quantita <= $prodotto->minimo_scorta) {
            // No hacemos nada aquí porque al redirigir a index, ya se mostrará
            // el botón de Bring! si hay productos con bajo stock
        }

        return redirect()->route('prodotti.index')->with('success', 'Prodotto aggiunto con successo!');
    }

    public function edit(Prodotto $prodotto)
    {
        return view('prodotti.edit', compact('prodotto'));
    }

    public function update(Request $request, Prodotto $prodotto)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'marca' => 'nullable|string|max:255',
            'categoria' => 'required|string|max:255',
            'quantita' => 'required|integer',
            'ubicazione' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255|unique:prodotti,barcode,'.$prodotto->id,
            'immagine' => 'nullable|string|max:500',
            'minimo_scorta' => 'nullable|integer|min:0',
            'note' => 'nullable|string|max:500'
        ]);

        $oldQuantity = $prodotto->quantita;
        $oldMinimoScorta = $prodotto->minimo_scorta;
        
        $prodotto->update($request->all());

        // Verificar si el producto ahora está por debajo del stock mínimo
        $wasLowStock = $oldQuantity > $oldMinimoScorta;
        $isLowStock = $prodotto->quantita <= $prodotto->minimo_scorta;
        
        if (!$wasLowStock && $isLowStock) {
            // Al redirigir a index, ya se mostrará el botón de Bring! automáticamente
        }

        return redirect()->route('prodotti.index')->with('success', 'Prodotto aggiornato con successo!');
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            // Busco el producto por ID de forma explícita
            $prodotto = Prodotto::findOrFail($id);
            
            // Elimino utilizando el método de Eloquent
            $prodotto->delete();
            
            // Verifico que el producto ya no exista en la base de datos
            if (Prodotto::find($id)) {
                // Si el producto sigue existiendo después de delete(), hay un problema
                // Intento una eliminación directa con query builder
                DB::table('prodotti')->where('id', $id)->delete();
                
                // Verifico nuevamente
                if (DB::table('prodotti')->where('id', $id)->exists()) {
                    throw new \Exception('No se pudo eliminar el producto de la base de datos');
                }
            }
            
            DB::commit();
            
            // Retorno respuesta según el tipo de solicitud
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Prodotto eliminato con successo!']);
            }
            
            return redirect()->route('prodotti.index')->with('success', 'Prodotto eliminato con successo!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar producto', [
                'id' => $id, 
                'error' => $e->getMessage()
            ]);
            
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Errore: ' . $e->getMessage()], 500);
            }
            
            return redirect()->route('prodotti.index')->with('error', 'Errore durante l\'eliminazione: ' . $e->getMessage());
        }
    }

    /**
     * Añadir productos con bajo stock a la lista de compras
     */
    public function addToShoppingList(Request $request)
    {
        $request->validate([
            'products' => 'required',
        ]);
        
        try {
            DB::beginTransaction();
            
            $productsData = json_decode($request->products);
            $addedCount = 0;
            $duplicateCount = 0;
            
            foreach ($productsData as $product) {
                // Verificar si ya existe en la lista de compras
                $exists = ShoppingListItem::where('prodotto_id', $product->id)
                                        ->where('purchased', false)
                                        ->exists();
                
                if (!$exists) {
                    ShoppingListItem::create([
                        'nome' => $product->nome,
                        'marca' => $product->marca,
                        'categoria' => $product->categoria,
                        'quantita' => max(1, $product->minimo_scorta - $product->quantita),
                        'immagine' => $product->immagine,
                        'prodotto_id' => $product->id
                    ]);
                    $addedCount++;
                } else {
                    $duplicateCount++;
                }
            }
            
            DB::commit();
            
            return redirect()->route('shopping.list')->with('success', 
                "Aggiunti $addedCount prodotti alla lista della spesa" . 
                ($duplicateCount > 0 ? " ($duplicateCount già presenti)" : ""));
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al añadir productos a la lista de compras', [
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Errore durante l\'aggiunta dei prodotti alla lista: ' . $e->getMessage());
        }
    }

    /**
     * Generar enlace de Bring! para productos de bajo stock
     */
    public function generateBringLink(Request $request)
    {
        $daysToCheck = $request->input('days', null);
        $bringData = $this->bringService->generateLowStockLink($daysToCheck);
        
        return response()->json($bringData);
    }

    /**
     * Verificar todos los productos con bajo stock y generar enlace a Bring!
     */
    public function checkAllLowStock()
    {
        $bringData = $this->bringService->generateLowStockLink();
        
        if ($bringData['has_products']) {
            return response()->json([
                'success' => true,
                'message' => "Se encontraron {$bringData['count']} productos con bajo stock",
                'bring_link' => $bringData['link'],
                'count' => $bringData['count']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "No hay productos con bajo stock",
                'count' => 0
            ]);
        }
    }
}