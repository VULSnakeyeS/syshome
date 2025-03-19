<?php

namespace App\Http\Controllers;

use App\Models\Compito;
use App\Models\User;
use Illuminate\Http\Request;

class CompitoController extends Controller
{
    public function index()
    {
        $compiti_pendenti = Compito::where('completato', false)->latest()->limit(5)->get();
        $compiti_completati = Compito::where('completato', true)->latest()->limit(5)->get();

        return view('compiti.index', compact('compiti_pendenti', 'compiti_completati'));
    }

    public function create()
    {
        $utenti = User::where('active', 1)->get(); // Solo usuarios activos
        return view('compiti.create', compact('utenti'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titolo' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'data_compito' => 'required|date',
            'assegnato_a' => 'required|string',
        ]);

        Compito::create([
            'titolo' => $request->titolo,
            'descrizione' => $request->descrizione,
            'data_compito' => $request->data_compito,
            'assegnato_a' => $request->assegnato_a,
            'completato' => false,
        ]);

        return redirect()->route('compiti.index')->with('success', 'Compito creato con successo.');
    }

    public function edit(Compito $compito)
    {
        $utenti = User::where('active', 1)->get();
        return view('compiti.edit', compact('compito', 'utenti'));
    }

    public function update(Request $request, Compito $compito)
    {
        $request->validate([
            'titolo' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'data_compito' => 'required|date',
            'assegnato_a' => 'required|string',
        ]);

        $compito->update([
            'titolo' => $request->titolo,
            'descrizione' => $request->descrizione,
            'data_compito' => $request->data_compito,
            'assegnato_a' => $request->assegnato_a,
            'completato' => $request->has('completato') ? 1 : 0,
        ]);

        return redirect()->route('compiti.index')->with('success', 'Compito aggiornato con successo.');
    }

    public function destroy(Compito $compito)
    {
        $compito->delete();
        return redirect()->route('compiti.index')->with('success', 'Compito eliminato con successo.');
    }

    public function toggleStatus(Compito $compito)
    {
        $compito->update(['completato' => !$compito->completato]);
        return redirect()->route('compiti.index')->with('success', 'Stato del compito aggiornato.');
    }
}
