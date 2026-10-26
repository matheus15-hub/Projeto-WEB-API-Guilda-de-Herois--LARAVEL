<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AventureiroRequest;
use App\Http\Requests\AventureiroUpdateRequest;
use App\Services\AventureiroService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AventureiroController extends Controller
{
    public function __construct(private AventureiroService $aventureiroService){}
    

    public function index() : JsonResponse
    {
        $avent = $this->aventureiroService->getAventureiroAll();
        return response()->json($avent);
    }

    public function store(AventureiroRequest $request): JsonResponse
{
    $id = $this->aventureiroService->createAventureiro(  $request->validated());

    return response()->json([
        'mensagem' => 'Aventureiro criado com sucesso!',
        'Aventureiro::' => $id
    ], 201);
}
    
    
    public function show(int $id): JsonResponse
    {
        $avent = $this->aventureiroService->getAventureiroByID($id);
        return response()->json($avent);
    }


    public function update(int $id, AventureiroUpdateRequest $request) : JsonResponse
    {
        $avent = $this->aventureiroService->updateAventureiroById($id , $request->validated());
        return response()->json($avent, 200);
    }

    public function destroy(int $id) : JsonResponse
    {
        $avent = $this->aventureiroService->deleteAventureiroById($id);
        return response()->json(null,204);
    }
}
