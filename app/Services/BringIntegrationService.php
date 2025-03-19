<?php

namespace App\Services;

use App\Models\Prodotto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class BringIntegrationService
{
    /**
     * Genera una URL para añadir productos a Bring! usando el formato actual
     * 
     * @param Collection|Prodotto[] $products Productos para añadir a la lista
     * @param string|null $listUuid UUID de la lista de Bring! (opcional)
     * @return string URL para Bring!
     */
    public function generateBringUrl($products, ?string $listUuid = null)
    {
        // Asegurarnos de que tenemos productos para añadir
        if ($products->isEmpty()) {
            return null;
        }

        // Preparamos el objeto de productos
        $itemsData = [];
        foreach ($products as $product) {
            $itemData = [
                "name" => trim($product->nome)
            ];
            
            // Añadir especificación (marca) si existe
            if (!empty($product->marca)) {
                $itemData["specification"] = trim($product->marca);
            }
            
            $itemsData[] = $itemData;
        }
        
        // Construimos el objeto completo según el formato actual de Bring!
        $payload = [
            "itemsToAdd" => $itemsData
        ];
        
        // Añadir UUID de lista si existe
        if ($listUuid) {
            $payload["listUuid"] = $listUuid;
        }
        
        // La URL base actualizada de Bring!
        $baseUrl = "https://web.getbring.com/items/import";
        
        // Codificar el payload completo
        $jsonPayload = json_encode($payload);
        $encodedPayload = urlencode($jsonPayload);
        
        // Construir la URL final
        $url = $baseUrl . "?json=" . $encodedPayload;
        
        return $url;
    }

    /**
     * Genera un enlace para abrir Bring! con productos de bajo stock
     * 
     * @param int|null $daysToCheck Verificar productos con bajo stock en los últimos X días
     * @return array Información del enlace y productos
     */
    public function generateLowStockLink(?int $daysToCheck = null)
    {
        // Obtener productos con bajo stock
        $query = Prodotto::whereRaw('quantita <= minimo_scorta AND minimo_scorta > 0');
        
        // Si se especifica un período, limitar a cambios recientes
        if ($daysToCheck) {
            $query->where('updated_at', '>=', now()->subDays($daysToCheck));
        }
        
        $lowStockProducts = $query->get();

        if ($lowStockProducts->isEmpty()) {
            return [
                'has_products' => false,
                'count' => 0,
                'link' => null,
                'products' => []
            ];
        }

        // Generar la URL para Bring!
        $bringUrl = $this->generateBringUrl($lowStockProducts);

        return [
            'has_products' => true,
            'count' => $lowStockProducts->count(),
            'link' => $bringUrl,
            'products' => $lowStockProducts
        ];
    }
}