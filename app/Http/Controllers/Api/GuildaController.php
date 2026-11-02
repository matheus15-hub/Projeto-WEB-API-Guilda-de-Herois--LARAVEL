<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuildaRequest;
use App\Services\GuildaService;
use Illuminate\Http\JsonResponse;

class GuildaController extends Controller
{
    public function __construct(
        private GuildaService $guildaService
    ) {}

    public function index(): JsonResponse
    {
        $guilda = $this->guildaService->getGuildaAll();

        return response()->json([
            'message' => 'Guildas encontradas com sucesso!',
            'data' => $guilda
        ], 200);
    }

    public function store(GuildaRequest $request): JsonResponse
    {
        $guilda = $this->guildaService->createGuilda(
            $request->validated()
        );

        return response()->json([
            'message' => 'Guilda criada com sucesso!',
            'data' => $guilda
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $guilda = $this->guildaService->getGuildaById($id);

        return response()->json([
            'message' => 'Guilda encontrada com sucesso!',
            'data' => $guilda
        ], 200);
    }

    public function update(int $id, GuildaRequest $request): JsonResponse
    {
        $guildaAntiga = $this->guildaService->getGuildaById($id);

        $guildaAtualizada = $this->guildaService->updateGuildaById(
            $id,
            $request->validated()
        );

        return response()->json([
            'message' => 'Guilda atualizada com sucesso!',
            'data' => [
                'antes' => $guildaAntiga,
                'depois' => $guildaAtualizada
            ]
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $guilda = $this->guildaService->getGuildaById($id);

        $this->guildaService->deleteGuildaById($id);

        return response()->json([
            'message' => 'Guilda deletada com sucesso!',
            'data' => $guilda
        ], 200);
    }

    public function ouro(int $idGuilda): JsonResponse
    {
        $total = $this->guildaService->calcularOuroGuilda($idGuilda);

        return response()->json([
            'message' => 'Ouro da guilda consultado com sucesso!',
            'data' => [
                'id_guilda' => $idGuilda,
                'ouro_total' => $total
            ]
        ], 200);
    }

    public function missoesCompletadas(int $idGuilda): JsonResponse
    {
        $missoes = $this->guildaService
            ->listarMissoesCompletadasGuilda($idGuilda);

        return response()->json([
            'message' => 'Missões completadas encontradas com sucesso!',
            'data' => $missoes
        ], 200);
    }
}
