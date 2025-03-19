<?php

namespace App\Http\Controllers;

use App\Models\Animale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnimaleController extends Controller
{
    public function index()
    {
        $animali = Animale::all() ?? collect(); // ✅ Se asegura de que nunca sea null
        return view('animali.index', compact('animali'));
    }

    public function create()
    {
        return view('animali.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'specie' => 'required|string|in:Cane,Gatto',
            'razza' => 'nullable|string|max:255',
            'data_nascita' => 'nullable|date',
            'note' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('animali', 'public');
        }

        Animale::create([
            'nome' => $request->nome,
            'specie' => $request->specie,
            'razza' => $request->razza,
            'data_nascita' => $request->data_nascita,
            'note' => $request->note,
            'foto' => $fotoPath
        ]);

        return redirect()->route('animali.index')->with('success', 'Animale aggiunto con successo.');
    }

    public function show(Animale $animale)
    {
        $animale->loadMissing('visiteVeterinarie'); // ✅ Cargar las visitas
        $visite = $animale->visiteVeterinarie ?? collect(); // ✅ Si no hay visitas, pasa una colección vacía

        return view('animali.show', compact('animale', 'visite'));
    }

    public function edit(Animale $animale)
    {
        return view('animali.edit', compact('animale'));
    }

    public function update(Request $request, Animale $animale)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'specie' => 'required|string|in:Cane,Gatto',
            'razza' => 'nullable|string|max:255',
            'data_nascita' => 'nullable|date',
            'note' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            if ($animale->foto) {
                Storage::disk('public')->delete($animale->foto);
            }
            $fotoPath = $request->file('foto')->store('animali', 'public');
            $animale->foto = $fotoPath;
        }

        $animale->update([
            'nome' => $request->nome,
            'specie' => $request->specie,
            'razza' => $request->razza,
            'data_nascita' => $request->data_nascita,
            'note' => $request->note,
            'foto' => $animale->foto
        ]);

        return redirect()->route('animali.index')->with('success', 'Animale aggiornato con successo.');
    }

    public function destroy(Animale $animale)
    {
        if ($animale->foto) {
            Storage::disk('public')->delete($animale->foto);
        }

        $animale->delete();

        return redirect()->route('animali.index')->with('success', 'Animale eliminato con successo.');
    }
}
















