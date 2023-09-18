<?php

namespace App\Http\Controllers\Api;

use App\Models\Chapa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChapaItemResource;
use App\Http\Resources\ChapaItemCollection;

class ChapaChapaItemsController extends Controller
{
    public function index(Request $request, Chapa $chapa): ChapaItemCollection
    {
        $this->authorize('view', $chapa);

        $search = $request->get('search', '');

        $chapaItems = $chapa
            ->chapaItems()
            ->search($search)
            ->latest()
            ->paginate();

        return new ChapaItemCollection($chapaItems);
    }

    public function store(Request $request, Chapa $chapa): ChapaItemResource
    {
        $this->authorize('create', ChapaItem::class);

        $validated = $request->validate([
            'largura' => ['required', 'max:255', 'string'],
            'comprimento' => ['required', 'max:255', 'string'],
        ]);

        $chapaItem = $chapa->chapaItems()->create($validated);

        return new ChapaItemResource($chapaItem);
    }
}
