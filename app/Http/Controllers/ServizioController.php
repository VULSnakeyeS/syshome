<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servizio;
use Illuminate\Support\Facades\Storage;

class ServizioController extends Controller
{
    public function index(Request $request)
    {
        $query = Servizio::query();
        $totalFattura = null;

        // Búsqueda por texto
        if ($request->filled('search')) {
            $query->where('bol_fatt', 'like', '%' . $request->search . '%')
                  ->orWhere('commenti', 'like', '%' . $request->search . '%');
        }

        // Filtro por intervalo de fechas
        if ($request->filled('data_fattura_inicio') && $request->filled('data_fattura_fine')) {
            $query->whereBetween('data_fattura', [$request->data_fattura_inicio, $request->data_fattura_fine]);
            $totalFattura = $query->sum('totale_fattura');
        }

        $servizi = $query->get();

        return view('servizi.index', compact('servizi', 'totalFattura'));
    }

    public function create()
    {
        return view('servizi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'servizio' => 'required|in:Luce,Acqua,Gas,Internet',
            'bol_fatt' => 'required|max:50',
            'totale_fattura' => 'required|numeric|between:0,99999999999999999999.99',
            'data_fattura' => 'required|date',
            'data_scadenza' => 'required|date',
            'pdf_path' => 'nullable|file|mimes:pdf',
            'commenti' => 'nullable|string'
        ]);

        $data = $request->all();

        if ($request->hasFile('pdf_path')) {
            $data['pdf_path'] = $request->file('pdf_path')->store('fatture', 'public');
        }

        Servizio::create($data);

        return redirect()->route('servizi.index')->with('success', 'Servizio aggiunto con successo!');
    }

    public function show($id)
    {
        $servizio = Servizio::findOrFail($id);
        return view('servizi.show', compact('servizio'));
    }

    public function edit($id)
    {
        $servizio = Servizio::findOrFail($id);
        return view('servizi.edit', compact('servizio'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'servizio' => 'required|in:Luce,Acqua,Gas,Internet',
            'bol_fatt' => 'required|max:50',
            'totale_fattura' => 'required|numeric|between:0,99999999999999999999.99',
            'data_fattura' => 'required|date',
            'data_scadenza' => 'required|date',
            'pdf_path' => 'nullable|file|mimes:pdf',
            'commenti' => 'nullable|string'
        ]);

        $servizio = Servizio::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('pdf_path')) {
            if ($servizio->pdf_path && Storage::disk('public')->exists($servizio->pdf_path)) {
                Storage::disk('public')->delete($servizio->pdf_path);
            }
            $data['pdf_path'] = $request->file('pdf_path')->store('fatture', 'public');
        }

        $servizio->update($data);

        return redirect()->route('servizi.index')->with('success', 'Servizio aggiornato con successo!');
    }

    public function destroy($id)
    {
        $servizio = Servizio::findOrFail($id);

        if ($servizio->pdf_path && Storage::disk('public')->exists($servizio->pdf_path)) {
            Storage::disk('public')->delete($servizio->pdf_path);
        }

        $servizio->delete();

        return redirect()->route('servizi.index')->with('success', 'Servizio eliminato con successo!');
    }
}




