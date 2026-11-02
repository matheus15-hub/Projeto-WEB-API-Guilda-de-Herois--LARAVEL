<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MonstroRequest;
use App\Services\MonstroService;
use Illuminate\Http\JsonResponse;

class MonstroController extends Controller
{
    public function __construct(
        private MonstroService $monstroService
    ) {}

    public function index(): JsonResponse
    {
        $monstro = $this->monstroService->getMonstroAll();

        return response()->json([
            'message' => 'Monstros encontrados com sucesso!',
            'data' => $monstro
        ], 200);
    }

    public function store(MonstroRequest $request): JsonResponse
    {
        $monstro = $this->monstroService->createMonstro(
            $request->validated()
        );

        return response()->json([
            'message' => 'Monstro criado com sucesso!',
            'data' => $monstro
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $monstro = $this->monstroService->getMonstroById($id);

        return response()->json([
            'message' => 'Monstro encontrado com sucesso!',
            'data' => $monstro
        ], 200);
    }

    public function update(int $id, MonstroRequest $request): JsonResponse
    {
        $monstroAntigo = $this->monstroService->getMonstroById($id);

        $monstroAtualizado = $this->monstroService->updateMonstroById(
            $id,
            $request->validated()
        );

        return response()->json([
            'message' => 'Monstro atualizado com sucesso!',
            'data' => [
                'antes' => $monstroAntigo,
                'depois' => $monstroAtualizado
            ]
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $monstro = $this->monstroService->getMonstroById($id);

        $this->monstroService->deleteMonstroById($id);

        return response()->json([
            'message' => 'Monstro deletado com sucesso!',
            'data' => $monstro
        ], 200);
    }
}
