<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MissoesCompletasRequest;
use App\Http\Requests\MissoesCompletasUpadateRequest;
use App\Services\MissoesCompletadasService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MissoesCompletadasController extends Controller
{
    public function __construct(
        private MissoesCompletadasService $missoesCompletadas
    ) {}

    public function index(Request $request): JsonResponse
    {
        $porPagina = $request->integer('per_page', 10);

        $porPagina = min(max($porPagina, 1), 200);

        $missao = $this->missoesCompletadas
            ->getMissoesCompletasAll($porPagina);

        return response()->json([
            'message' => 'Missões completadas encontradas com sucesso!',
            'data' => $missao
        ], 200);
    }

    public function store(MissoesCompletasRequest $request): JsonResponse
    {
        $missao = $this->missoesCompletadas
            ->createMissaoCompleta($request->validated());

        return response()->json([
            'message' => 'Missão completada registrada com sucesso!',
            'data' => $missao
        ], 201);
    }

    public function show(int $idMisso, int $idAve): JsonResponse
    {
        $missao = $this->missoesCompletadas
            ->getMissaoAventureiroById($idMisso, $idAve);

        return response()->json([
            'message' => 'Missão completada encontrada com sucesso!',
            'data' => $missao
        ], 200);
    }

    public function update(
        int $idMisso,
        int $idAve,
        MissoesCompletasUpadateRequest $request
    ): JsonResponse {

        $missaoAntiga = $this->missoesCompletadas
            ->getMissaoAventureiroById($idMisso, $idAve);

        $missaoAtualizada = $this->missoesCompletadas
            ->upadateMissaoAventureirotaById(
                $idMisso,
                $idAve,
                $request->validated()
            );

        return response()->json([
            'message' => 'Missão completada atualizada com sucesso!',
            'data' => [
                'antes' => $missaoAntiga,
                'depois' => $missaoAtualizada
            ]
        ], 200);
    }

    public function concluidasPorAventureiro(int $id): JsonResponse
    {
        $missoes = $this->missoesCompletadas
            ->listarConcluidasPorAventureiro($id);

        return response()->json([
            'message' => 'Histórico de missões encontrado com sucesso!',
            'data' => $missoes
        ], 200);
    }

    public function disponiveisPorAventureiro(int $id): JsonResponse
    {
        $missoes = $this->missoesCompletadas
            ->listarDisponiveisPorAventureiro($id);

        return response()->json([
            'message' => 'Missões disponíveis encontradas com sucesso!',
            'data' => $missoes
        ], 200);
    }

    public function destroy(int $idMisso, int $idAve): JsonResponse
    {
        $missao = $this->missoesCompletadas
            ->getMissaoAventureiroById($idMisso, $idAve);

        $this->missoesCompletadas
            ->deleteMissaoAventureiroById($idMisso, $idAve);

        return response()->json([
            'message' => 'Missão completada deletada com sucesso!',
            'data' => $missao
        ], 200);
    }
}
