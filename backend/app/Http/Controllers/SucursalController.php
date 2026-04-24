<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSucursalRequest;
use App\Http\Requests\UpdateSucursalRequest;
use App\Http\Resources\SucursalResource;
use App\Http\Resources\EmpleadoResource;
use App\Services\SucursalService;
use App\Services\EmpleadoService;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SucursalController extends Controller
{
    use ApiResponder;

    public function __construct(
        protected SucursalService $sucursalService,
        protected EmpleadoService $empleadoService
    ) {}

    #[OA\Get(
        path: "/api/sucursales",
        summary: "List all branches",
        tags: ["Sucursales"],
        parameters: [
            new OA\Parameter(name: "search", in: "query", description: "Search by name or city", schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "page", in: "query", description: "Page number", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Success")
        ]
    )]
    public function index(Request $request)
    {
        $sucursales = $this->sucursalService->getAll($request->all());
        return SucursalResource::collection($sucursales);
    }

    #[OA\Get(
        path: "/api/sucursales/{id}/empleados",
        summary: "Get paginated employees of a branch",
        tags: ["Sucursales"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer")),
            new OA\Parameter(name: "page", in: "query", description: "Page number", schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Success")
        ]
    )]
    public function employees(int $id, Request $request)
    {
        $filters = $request->only(['search', 'page']);
        $filters['sucursal_id'] = $id;
        $employees = $this->empleadoService->getAll($filters);
        return EmpleadoResource::collection($employees);
    }

    #[OA\Post(
        path: "/api/sucursales",
        summary: "Create a new branch",
        tags: ["Sucursales"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["nombre", "ciudad", "pais", "latitud", "longitud"],
                properties: [
                    new OA\Property(property: "nombre", type: "string"),
                    new OA\Property(property: "ciudad", type: "string"),
                    new OA\Property(property: "pais", type: "string"),
                    new OA\Property(property: "latitud", type: "number", format: "float"),
                    new OA\Property(property: "longitud", type: "number", format: "float")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Created"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(StoreSucursalRequest $request)
    {
        $sucursal = $this->sucursalService->create($request->validated());
        return $this->success("Sucursal creada correctamente", new SucursalResource($sucursal), 201);
    }

    #[OA\Get(
        path: "/api/sucursales/{id}",
        summary: "Get branch details with weather",
        tags: ["Sucursales"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Success"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function show(int $id)
    {
        $sucursal = $this->sucursalService->getById($id);
        return $this->success("Datos de la sucursal", new SucursalResource($sucursal));
    }

    #[OA\Put(
        path: "/api/sucursales/{id}",
        summary: "Update an existing branch",
        tags: ["Sucursales"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "nombre", type: "string"),
                    new OA\Property(property: "ciudad", type: "string"),
                    new OA\Property(property: "pais", type: "string"),
                    new OA\Property(property: "latitud", type: "number", format: "float"),
                    new OA\Property(property: "longitud", type: "number", format: "float")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Updated"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function update(UpdateSucursalRequest $request, int $id)
    {
        $sucursal = $this->sucursalService->update($id, $request->validated());
        return $this->success("Sucursal actualizada correctamente", new SucursalResource($sucursal));
    }

    #[OA\Delete(
        path: "/api/sucursales/{id}",
        summary: "Delete a branch",
        tags: ["Sucursales"],
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
        $this->sucursalService->delete($id);
        return $this->success("Sucursal eliminada correctamente");
    }
}
