<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmpleadoRequest;
use App\Http\Requests\UpdateEmpleadoRequest;
use App\Http\Resources\EmpleadoResource;
use App\Services\EmpleadoService;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class EmpleadoController extends Controller
{
    use ApiResponder;

    public function __construct(
        protected EmpleadoService $empleadoService
    ) {}

    #[OA\Get(
        path: "/api/empleados",
        summary: "List all employees",
        tags: ["Empleados"],
        parameters: [
            new OA\Parameter(name: "sucursal_id", in: "query", description: "Filter by branch ID", schema: new OA\Schema(type: "integer")),
            new OA\Parameter(name: "page", in: "query", description: "Page number", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Success")
        ]
    )]
    public function index(Request $request)
    {
        $empleados = $this->empleadoService->getAll($request->all());
        return EmpleadoResource::collection($empleados);
    }

    #[OA\Post(
        path: "/api/empleados",
        summary: "Create a new employee",
        tags: ["Empleados"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["nombre", "email", "sucursal_id"],
                properties: [
                    new OA\Property(property: "nombre", type: "string"),
                    new OA\Property(property: "email", type: "string", format: "email"),
                    new OA\Property(property: "sucursal_id", type: "integer")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Created"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(StoreEmpleadoRequest $request)
    {
        $empleado = $this->empleadoService->create($request->validated());
        return $this->success("Empleado creado correctamente", new EmpleadoResource($empleado), 201);
    }

    #[OA\Put(
        path: "/api/empleados/{id}",
        summary: "Update an existing employee",
        tags: ["Empleados"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "nombre", type: "string"),
                    new OA\Property(property: "email", type: "string", format: "email"),
                    new OA\Property(property: "sucursal_id", type: "integer")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Updated"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function update(UpdateEmpleadoRequest $request, int $id)
    {
        $empleado = $this->empleadoService->update($id, $request->validated());
        return $this->success("Empleado actualizado correctamente", new EmpleadoResource($empleado));
    }

    #[OA\Delete(
        path: "/api/empleados/{id}",
        summary: "Delete an employee",
        tags: ["Empleados"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Deleted"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function destroy(int $id)
    {
        $this->empleadoService->delete($id);
        return $this->success("Empleado eliminado correctamente");
    }
}
