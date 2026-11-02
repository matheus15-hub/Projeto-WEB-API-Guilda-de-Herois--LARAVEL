<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClasseRequest;
use App\Services\ClasseService;
use Illuminate\Http\JsonResponse;

class ClasseController extends Controller
{
    public function __construct(
        private ClasseService $classeService
    ) {}

    public function index(): JsonResponse
    {
        $classe = $this->classeService->getClasseAll();

        return response()->json([
            'message' => 'Classes encontradas com sucesso!',
            'data' => $classe
        ], 200);
    }

    public function store(ClasseRequest $request): JsonResponse
    {
        $classe = $this->classeService->createClasse(
            $request->validated()
        );

        return response()->json([
            'message' => 'Classe criada com sucesso!',
            'data' => $classe
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $classe = $this->classeService->getClasseById($id);

        return response()->json([
            'message' => 'Classe encontrada com sucesso!',
            'data' => $classe
        ], 200);
    }

    public function update(int $id, ClasseRequest $request): JsonResponse
    {
        $classeAntiga = $this->classeService->getClasseById($id);

        $classeAtualizada = $this->classeService->updateClasseById(
            $id,
            $request->validated()
        );

        return response()->json([
            'message' => 'Classe atualizada com sucesso!',
            'data' => [
                'antes' => $classeAntiga,
                'depois' => $classeAtualizada
            ]
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $classe = $this->classeService->getClasseById($id);

        $this->classeService->deleteClasseById($id);

        return response()->json([
            'message' => 'Classe deletada com sucesso!',
            'data' => $classe
        ], 200);
    }
}
