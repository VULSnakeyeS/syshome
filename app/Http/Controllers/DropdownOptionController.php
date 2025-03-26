<?php

namespace App\Http\Controllers;

use App\Models\DropdownOption;
use Illuminate\Http\Request;

class DropdownOptionController extends Controller
{
    public function index()
    {
        $dropdownOptions = DropdownOption::all();
        return view('dropdown-options.index', compact('dropdownOptions'));
    }

    public function create()
    {
        return view('dropdown-options.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'dropdown_name' => 'required|string',
            'option_value' => 'required|string',
        ]);

        DropdownOption::create($request->all());
        return redirect()->route('dropdown-options.index')->with('success', 'Opción creada con éxito.');
    }

    public function edit(DropdownOption $dropdownOption)
    {
        return view('dropdown-options.edit', compact('dropdownOption'));
    }

    public function update(Request $request, DropdownOption $dropdownOption)
    {
        $request->validate([
            'dropdown_name' => 'required|string',
            'option_value' => 'required|string',
        ]);

        $dropdownOption->update($request->all());
        return redirect()->route('dropdown-options.index')->with('success', 'Opción actualizada con éxito.');
    }

    public function destroy(DropdownOption $dropdownOption)
    {
        $dropdownOption->delete();
        return redirect()->route('dropdown-options.index')->with('success', 'Opción eliminada con éxito.');
    }
}


