<?php

namespace App\Http\Controllers;

use App\Models\ShoppingListItem;
use App\Models\Prodotto;
use Illuminate\Http\Request;

class ShoppingListController extends Controller
{
    public function index()
    {
        // Obtener items agrupados por categoría
        $items = ShoppingListItem::where('purchased', false)
                                ->orderBy('categoria')
                                ->orderBy('nome')
                                ->get();
                                
        $itemsByCategory = $items->groupBy('categoria');
        
        // Obtener items comprados
        $purchasedItems = ShoppingListItem::where('purchased', true)->count();
        $totalItems = $items->count() + $purchasedItems;
        
        return view('shopping.index', compact('itemsByCategory', 'totalItems', 'purchasedItems'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'quantita' => 'required|integer|min:1',
            'categoria' => 'required|string|max:255',
        ]);
        
        ShoppingListItem::create($request->all());
        
        return redirect()->route('shopping.list')->with('success', 'Prodotto aggiunto alla lista della spesa!');
    }
    
    public function markAsPurchased(Request $request, $id)
    {
        $item = ShoppingListItem::findOrFail($id);
        $item->purchased = true;
        $item->save();
        
        // Si está vinculado a un producto, actualizar el stock
        if ($item->prodotto_id) {
            $prodotto = Prodotto::find($item->prodotto_id);
            if ($prodotto) {
                $prodotto->quantita += $item->quantita;
                $prodotto->save();
            }
        }
        
        return redirect()->route('shopping.list')->with('success', 'Prodotto segnato come acquistato!');
    }
    
    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantita' => 'required|integer|min:1',
        ]);
        
        $item = ShoppingListItem::findOrFail($id);
        $item->quantita = $request->quantita;
        $item->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Quantità aggiornata!',
            'new_quantity' => $item->quantita
        ]);
    }
    
    public function destroy($id)
    {
        $item = ShoppingListItem::findOrFail($id);
        $item->delete();
        
        return redirect()->route('shopping.list')->with('success', 'Prodotto rimosso dalla lista!');
    }
    
    public function clearPurchased()
    {
        ShoppingListItem::where('purchased', true)->delete();
        
        return redirect()->route('shopping.list')->with('success', 'Prodotti acquistati rimossi dalla lista!');
    }
    
    public function addManualItem(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'quantita' => 'required|integer|min:1',
            'categoria' => 'required|string|max:255',
        ]);
        
        ShoppingListItem::create([
            'nome' => $request->nome,
            'marca' => $request->marca,
            'categoria' => $request->categoria,
            'quantita' => $request->quantita,
            'immagine' => $request->immagine,
        ]);
        
        return redirect()->route('shopping.list')->with('success', 'Prodotto aggiunto alla lista della spesa!');
    }
}