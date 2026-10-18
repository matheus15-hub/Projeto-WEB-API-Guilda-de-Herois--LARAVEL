<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuildaRequest;
use App\Services\GuildaService;
use Illuminate\Http\JsonResponse;


class GuildaController extends Controller
{
    public function __construct(private GuildaService $guildaService) { }    

    public function index() : JsonResponse
    {
       $guida = $this->guildaService->getGuildaAll();
       return response()->json($guida);
    }

    public function store(GuildaRequest $request): JsonResponse
    {
        $guilda = $this->guildaService->createGuilda($request->validated());

        return response()->json($guilda, 201);
    }

    public function show(int $id) : JsonResponse
    {
        $guilda = $this->guildaService->getGuildaById($id);
        
        return response()->json($guilda);
    }


    public function update(int $id , GuildaRequest $request): JsonResponse
    {
        $guilda = $this->guildaService->updateGuildaById($id, $request->validated());
        return response()->json($guilda, 204);
    }

    public function destroy(string $id) : JsonResponse
    {
        $this->guildaService->deleteGuildaById($id);
        return response()->json(null, 204 );
    }
}
