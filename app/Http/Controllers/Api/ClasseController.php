<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClasseRequest;
use App\Services\ClasseService;
use Illuminate\Http\JsonResponse;


class ClasseController extends Controller
{
    
    public function __construct(private ClasseService $classeService) { }

    public function index(): JsonResponse
    {
       $classe = $this->classeService->getClasseAll();
       
       return response()->Json($classe);
    }

    
    public function store(ClasseRequest $request) : JsonResponse 
    {
        $classe = $this->classeService->createClasse($request->validated());
        
        return response()->json([
        'mesagem' => 'Classe criada com sucesso!',  
        $classe], 201);
    }

    
    public function show(int $id) : JsonResponse
    {
        $classe = $this->classeService->getClasseById($id);
        return response()->json($classe);
    }

    
    public function update(int $id, ClasseRequest $request) : JsonResponse
    {   
        $classeAntiga = $this->classeService->getClasseById($id);
        $classeAtualizada = $this->classeService->updateClasseById($id, $request->validated());
        return response()->json([
            'message' => "Cadastro {$id} foi alterado com sucesso!",
            'cadastro antigo' => $classeAntiga,
            'cadastro atualizado'=>$classeAtualizada],200);
    }

    public function destroy(int $id) : JsonResponse
    {
        $classe = $this->classeService->getClasseById($id);
        $this->classeService->deleteClasseById($id);
        return response()->json([
        'message' => "Classe foi deletada com sucesso",
        'cadastro deletado' => $classe
        ], 200);
    }
}
