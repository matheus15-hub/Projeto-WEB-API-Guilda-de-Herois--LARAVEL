<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AventureiroRequest;
use App\Http\Requests\AventureiroUpdateRequest;
use App\Interfaces\AventureiroInterface;
use Illuminate\Http\JsonResponse;

class AventureiroController extends Controller
{
    public function __construct(
        private AventureiroInterface $aventureiroService
    ) {}

    public function index(): JsonResponse
    {
        $avent = $this->aventureiroService->getAventureiroAll();

        return response()->json([
            'message' => 'Aventureiros encontrados com sucesso!',
            'data' => $avent
        ], 200);
    }

    public function store(AventureiroRequest $request): JsonResponse
    {
        $avent = $this->aventureiroService->createAventureiro(
            $request->validated()
        );

        return response()->json([
            'message' => 'Aventureiro criado com sucesso!',
            'data' => $avent
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $avent = $this->aventureiroService->getAventureiroByID($id);

        return response()->json([
            'message' => 'Aventureiro encontrado com sucesso!',
            'data' => $avent
        ], 200);
    }

    public function update(
        int $id,
        AventureiroUpdateRequest $request
    ): JsonResponse {

        $aventAntigo = $this->aventureiroService->getAventureiroByID($id);

        $aventAtualizado = $this->aventureiroService->updateAventureiroById(
            $id,
            $request->validated()
        );

        return response()->json([
            'message' => 'Aventureiro atualizado com sucesso!',
            'data' => [
                'antes' => $aventAntigo,
                'depois' => $aventAtualizado
            ]
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $avent = $this->aventureiroService->getAventureiroByID($id);

        $this->aventureiroService->deleteAventureiroById($id);

        return response()->json([
            'message' => 'Aventureiro deletado com sucesso!',
            'data' => $avent
        ], 200);
    }
}
