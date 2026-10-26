<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MissaoRequest;
use App\Services\MissaoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MissaoController extends Controller
{
    
    public function __construct(private MissaoService $missao_service){}
  

    public function index() : JsonResponse
    {
        $missao = $this->missao_service->getMissaoAll();
        return response()->json($missao);
    }

 
    public function store(MissaoRequest $request) : JsonResponse
    {
        $missao = $this->missao_service->createMissao($request->validated());
        return response()->json($missao,201);
    }

  
    public function show(int $id) : JsonResponse
    {
        return response()->json($this->missao_service->getMissaoById($id));
    }

    public function update(int $id, MissaoRequest $request) : JsonResponse
    {
        $missao = $this->missao_service->updateMissaoById($id, $request->validated());
        return response()->json($missao);
    }


    public function destroy(int $id) : JsonResponse
    {
        $this->missao_service->deleteMissaoById($id);
        return response()->json([
        'mensagem' => "Missao {$id} foi deletada"]);
    }
}
