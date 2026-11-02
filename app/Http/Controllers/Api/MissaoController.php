<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MissaoRequest;
use App\Services\MissaoService;
use Illuminate\Http\JsonResponse;

class MissaoController extends Controller
{
    public function __construct(
        private MissaoService $missaoService
    ) {}

    public function index(): JsonResponse
    {
        $missao = $this->missaoService->getMissaoAll();

        return response()->json([
            'message' => 'Missões encontradas com sucesso!',
            'data' => $missao
        ], 200);
    }

    public function store(MissaoRequest $request): JsonResponse
    {
        $missao = $this->missaoService->createMissao(
            $request->validated()
        );

        return response()->json([
            'message' => 'Missão criada com sucesso!',
            'data' => $missao
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $missao = $this->missaoService->getMissaoById($id);

        return response()->json([
            'message' => 'Missão encontrada com sucesso!',
            'data' => $missao
        ], 200);
    }

    public function update(int $id, MissaoRequest $request): JsonResponse
    {
        $missaoAntiga = $this->missaoService->getMissaoById($id);

        $missaoAtualizada = $this->missaoService->updateMissaoById(
            $id,
            $request->validated()
        );

        return response()->json([
            'message' => 'Missão atualizada com sucesso!',
            'data' => [
                'antes' => $missaoAntiga,
                'depois' => $missaoAtualizada
            ]
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $missao = $this->missaoService->getMissaoById($id);

        $this->missaoService->deleteMissaoById($id);

        return response()->json([
            'message' => 'Missão deletada com sucesso!',
            'data' => $missao
        ], 200);
    }
}
