<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SucursalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'ciudad' => $this->ciudad,
            'pais' => $this->pais,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'clima' => $this->when(isset($this->clima), $this->clima),
            'empleados_count' => $this->whenCounted('empleados'),
            'empleados' => EmpleadoResource::collection($this->whenLoaded('empleados')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
