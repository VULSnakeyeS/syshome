<?php

namespace App\Http\Controllers;

use App\Models\InventarioAnimale;
use App\Models\Animale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventarioAnimaleController extends Controller
{
    public function index($animale_id)
    {
        $animale = Animale::findOrFail($animale_id);
        $inventario = $animale->inventario()->latest()->get();

        return view('inventarioanimali.index', compact('animale', 'inventario'));
    }

    public function create($animale_id)
    {
        $animale = Animale::findOrFail($animale_id);
        $categorie = InventarioAnimale::categoriePerSpecie($animale->specie);

        return view('inventarioanimali.create', compact('animale', 'categorie'));
    }

    public function store(Request $request, $animale_id)
    {
        // ✅ Validación de los datos
        $request->validate([
            'categoria' => 'required|string|max:100',
            'marca' => 'nullable|string|max:100',
            'nome' => 'required|string|max:100',
            'quantita' => 'required|numeric|min:0|max:9999',
            'unita_misura' => 'required|string|max:20',
            'data_acquisto' => 'required|date',
            'data_scadenza' => 'nullable|date',
            'prezzo' => 'nullable|numeric|min:0|max:9999.99',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024'
        ]);

        $animale = Animale::findOrFail($animale_id);
        $fotoPath = null;

        // ✅ Si hay una foto, la subimos
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('inventario_animali', 'public');
        }

        // ✅ Si "Nessuna Scadenza" está activado, dejamos "data_scadenza" en NULL
        $dataScadenza = $request->has('no_scadenza') ? null : $request->data_scadenza;

        // ✅ Guardamos el producto en la base de datos
        InventarioAnimale::create([
            'animale_id' => $animale->id,
            'categoria' => $request->categoria,
            'marca' => $request->marca,
            'nome' => $request->nome,
            'quantita' => $request->quantita,
            'unita_misura' => $request->unita_misura,
            'data_acquisto' => $request->data_acquisto,
            'data_scadenza' => $dataScadenza,
            'prezzo' => $request->prezzo,
            'foto' => $fotoPath
        ]);

        return redirect()->route('inventarioanimali.index', $animale_id)
                         ->with('success', 'Elemento aggiunto all\'inventario con successo.');
    }

    public function edit($animale_id, $id)
    {
        $animale = Animale::findOrFail($animale_id);
        $inventario = InventarioAnimale::findOrFail($id);
        $categorie = InventarioAnimale::categoriePerSpecie($animale->specie);

        return view('inventarioanimali.edit', compact('animale', 'inventario', 'categorie'));
    }

    public function update(Request $request, $animale_id, $id)
    {
        // ✅ Validación de los datos
        $request->validate([
            'categoria' => 'required|string|max:100',
            'marca' => 'nullable|string|max:100',
            'nome' => 'required|string|max:100',
            'quantita' => 'required|numeric|min:0|max:9999',
            'unita_misura' => 'required|string|max:20',
            'data_acquisto' => 'required|date',
            'data_scadenza' => 'nullable|date',
            'prezzo' => 'nullable|numeric|min:0|max:9999.99',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024'
        ]);

        $inventario = InventarioAnimale::findOrFail($id);
        $animale = Animale::findOrFail($animale_id);

        if ($request->hasFile('foto')) {
            if ($inventario->foto) {
                Storage::disk('public')->delete($inventario->foto);
            }
            $fotoPath = $request->file('foto')->store('inventario_animali', 'public');
            $inventario->foto = $fotoPath;
        }

        // ✅ Si "Nessuna Scadenza" está activado, dejamos "data_scadenza" en NULL
        $dataScadenza = $request->has('no_scadenza') ? null : $request->data_scadenza;

        $inventario->update([
            'categoria' => $request->categoria,
            'marca' => $request->marca,
            'nome' => $request->nome,
            'quantita' => $request->quantita,
            'unita_misura' => $request->unita_misura,
            'data_acquisto' => $request->data_acquisto,
            'data_scadenza' => $dataScadenza,
            'prezzo' => $request->prezzo,
            'foto' => $inventario->foto
        ]);

        return redirect()->route('inventarioanimali.index', $animale_id)
                         ->with('success', 'Elemento aggiornato con successo.');
    }

    public function destroy($animale_id, $id)
    {
        $inventario = InventarioAnimale::findOrFail($id);

        if ($inventario->foto) {
            Storage::disk('public')->delete($inventario->foto);
        }

        $inventario->delete();

        return redirect()->route('inventarioanimali.index', $animale_id)
                         ->with('success', 'Elemento rimosso con successo.');
    }
}





