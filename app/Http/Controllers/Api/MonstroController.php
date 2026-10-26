<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MonstroRequest;
use App\Services\MonstroService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MonstroController extends Controller
{
    public function __construct(private MonstroService $monstroService){}    

    public function index() : JsonResponse
    {
        $monstro = $this->monstroService->getMonstroAll();
        return response()->json($monstro);
    }

    public function store(MonstroRequest $request) : JsonResponse
    {
        $monstro = $this->monstroService->createMonstro($request->validated());
        return response()->json($monstro, 201 );
    }

    public function show(int $id) : JsonResponse
    {
        $monstro = $this->monstroService->getMonstroById($id);
        return response()->json($monstro );
    }


    public function update(int $id, MonstroRequest $request) : JsonResponse
    {
        $monstro = $this->monstroService->updateMonstroById($id , $request->validated());
        return response()->json($monstro);
    }


    public function destroy(int $id) : JsonResponse
    {
        $monstro = $this->monstroService->deleteMonstroById($id);
        return response()->json(null, 204);
    }
}