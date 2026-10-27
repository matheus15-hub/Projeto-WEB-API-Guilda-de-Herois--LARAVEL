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
    public function __construct(private MissoesCompletadasService $missoes_completadas) {}
    public function index(Request $request) : JsonResponse
    {   
        $porPagina = $request->integer('per_page',10);

        $porPagina = min(max($porPagina,1),200);
        $missao = $this->missoes_completadas->getMissoesCompletasAll($porPagina);
        return response()->json($missao);
    }

    public function store(MissoesCompletasRequest $request) : JsonResponse
    {
        $missao = $this->missoes_completadas->createMissaoCompleta($request->validated());
        return response()->json($missao,201);
    }

    public function show(int $idMisso, int $idAve) : JsonResponse
    {
         $missao = $this->missoes_completadas->getMissaoAventureiroById($idMisso,$idAve);
        return response()->json($missao);
    }

    public function update(int $idMisso, int $idAve, MissoesCompletasUpadateRequest $request) : JsonResponse
    {
        $missao = $this->missoes_completadas->upadateMissaoAventureirotaById($idMisso, $idAve ,$request->validated());
        return response()->json($missao,200);
    }

   
    public function destroy(int $idMisso, int $idAve) : JsonResponse
    {
        $missao = $this->missoes_completadas->deleteMissaoAventureiroById($idMisso, $idAve);
        return response()->json([
            'mensagem' => "Deletação concluida de missoes_completas das chaves {$idMisso} (MISSAO) , {$idAve} (AVENTUREIRO)"
            ]
        );
    }
}
