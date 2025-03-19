<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BringRedirectController extends Controller
{
    /**
     * Redirige a Bring! con los productos adecuados
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function redirect(Request $request)
    {
        $products = json_decode($request->input('products'));
        
        if (empty($products)) {
            return redirect()->back()->with('error', 'Non ci sono prodotti da inviare a Bring!');
        }
        
        // Preparamos el formulario para enviar a Bring!
        return view('bring-redirect', [
            'products' => $products,
            'total' => count($products)
        ]);
    }
}