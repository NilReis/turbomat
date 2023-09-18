<?php

namespace App\Http\Controllers\Api;

use App\Models\Chapa;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChapaResource;
use App\Http\Resources\ChapaCollection;
use App\Http\Requests\ChapaStoreRequest;
use App\Http\Requests\ChapaUpdateRequest;

class ChapaController extends Controller
{
    public function index(Request $request): ChapaCollection
    {
        $this->authorize('view-any', Chapa::class);

        $search = $request->get('search', '');

        $chapas = Chapa::search($search)
            ->latest()
            ->paginate();

        return new ChapaCollection($chapas);
    }

    public function store(ChapaStoreRequest $request): ChapaResource
    {
        $this->authorize('create', Chapa::class);

        $validated = $request->validated();

        $chapa = Chapa::create($validated);

        return new ChapaResource($chapa);
    }

    public function show(Request $request, Chapa $chapa): ChapaResource
    {
        $this->authorize('view', $chapa);

        return new ChapaResource($chapa);
    }

    public function update(
        ChapaUpdateRequest $request,
        Chapa $chapa
    ): ChapaResource {
        $this->authorize('update', $chapa);

        $validated = $request->validated();

        $chapa->update($validated);

        return new ChapaResource($chapa);
    }

    public function destroy(Request $request, Chapa $chapa): Response
    {
        $this->authorize('delete', $chapa);

        $chapa->delete();

        return response()->noContent();
    }
}
