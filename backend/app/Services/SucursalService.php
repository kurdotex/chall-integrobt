<?php

namespace App\Services;

use App\Models\Sucursal;
use App\Contracts\WeatherServiceInterface;

class SucursalService
{
    public function __construct(
        protected WeatherServiceInterface $weatherService
    ) {}

    public function getAll(array $filters = [])
    {
        $query = Sucursal::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('ciudad', 'like', "%{$search}%")
                  ->orWhere('pais', 'like', "%{$search}%");
            });
        }

        return $query->paginate($filters['per_page'] ?? config('api.pagination.per_page'));
    }

    public function getById(int $id)
    {
        $sucursal = Sucursal::with('empleados')->findOrFail($id);
        
        try {
            $sucursal->clima = $this->weatherService->getCurrentWeather($sucursal->latitud, $sucursal->longitud);
        } catch (\Exception $e) {
            $sucursal->clima = null;
        }

        return $sucursal;
    }

    public function create(array $data)
    {
        return Sucursal::create($data);
    }

    public function update(int $id, array $data)
    {
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->update($data);
        return $sucursal;
    }

    public function delete(int $id)
    {
        $sucursal = Sucursal::findOrFail($id);
        return $sucursal->delete();
    }
}
