<?php

namespace App\Http\Controllers;

use App\Models\VisitaVeterinaria;
use App\Models\Animale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VisitaVeterinariaController extends Controller
{
    public function index($animale_id)
    {
        $animale = Animale::findOrFail($animale_id);
        $visite = $animale->visiteVeterinarie()->latest()->get(); // ✅ Corrección aquí
        return view('visite.index', compact('animale', 'visite'));
    }

    public function create($animale_id)
    {
        $animale = Animale::findOrFail($animale_id);
        return view('visite.create', compact('animale'));
    }

    public function store(Request $request, $animale_id)
    {
        $request->validate([
            'data_visita' => 'required|date',
            'tipo' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'documento' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $animale = Animale::findOrFail($animale_id);
        $path = null;

        if ($request->hasFile('documento')) {
            $path = $request->file('documento')->store('documenti_visite', 'public');
        }

        VisitaVeterinaria::create([
            'animale_id' => $animale->id,
            'data_visita' => $request->data_visita,
            'tipo' => $request->tipo,
            'descrizione' => $request->descrizione,
            'documento' => $path
        ]);

        return redirect()->route('visite.index', $animale_id)
                         ->with('success', 'Visita registrata con successo.');
    }

    public function show($animale_id, $id)
    {
        $animale = Animale::findOrFail($animale_id);
        $visita = VisitaVeterinaria::findOrFail($id);
        return view('visite.show', compact('animale', 'visita'));
    }

    public function edit($animale_id, $id)
    {
        $animale = Animale::findOrFail($animale_id);
        $visita = VisitaVeterinaria::findOrFail($id);
        return view('visite.edit', compact('animale', 'visita'));
    }

    public function update(Request $request, $animale_id, $id)
    {
        $request->validate([
            'data_visita' => 'required|date',
            'tipo' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'documento' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $visita = VisitaVeterinaria::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('documento')) {
            if ($visita->documento) {
                Storage::disk('public')->delete($visita->documento);
            }
            $data['documento'] = $request->file('documento')->store('documenti_visite', 'public');
        }

        $visita->update($data);

        return redirect()->route('visite.index', $animale_id)
                         ->with('success', 'Visita aggiornata con successo.');
    }

    public function destroy($animale_id, $id)
    {
        $visita = VisitaVeterinaria::findOrFail($id);

        if ($visita->documento) {
            Storage::disk('public')->delete($visita->documento);
        }

        $visita->delete();

        return redirect()->route('visite.index', $animale_id)
                         ->with('success', 'Visita eliminata con successo.');
    }
}



