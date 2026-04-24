<?php

namespace App\Services;

use App\Models\Empleado;

class EmpleadoService
{
    public function getAll(array $filters = [])
    {
        $query = Empleado::with('sucursal');

        if (!empty($filters['sucursal_id'])) {
            $query->where('sucursal_id', $filters['sucursal_id']);
        }

        return $query->paginate($filters['per_page'] ?? config('api.pagination.per_page'));
    }

    public function getById(int $id)
    {
        return Empleado::with('sucursal')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Empleado::create($data);
    }

    public function update(int $id, array $data)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update($data);
        return $empleado;
    }

    public function delete(int $id)
    {
        $empleado = Empleado::findOrFail($id);
        return $empleado->delete();
    }
}
