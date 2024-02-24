<?php

namespace App\Http\Controllers;

use App\Models\Chapa;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ChapaStoreRequest;
use App\Http\Requests\ChapaUpdateRequest;

class ChapaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Chapa::class);

        $search = $request->get('search', '');

        $chapas = Chapa::search($search)
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('app.chapas.index', compact('chapas', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Chapa::class);

        return view('app.chapas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChapaStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Chapa::class);

        $validated = $request->validated();

        $chapa = Chapa::create($validated);

        return redirect()
            ->route('chapas.edit', $chapa)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Chapa $chapa): View
    {
        $this->authorize('view', $chapa);

        return view('app.chapas.show', compact('chapa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Chapa $chapa): View
    {
        $this->authorize('update', $chapa);

        return view('app.chapas.edit', compact('chapa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ChapaUpdateRequest $request,
        Chapa $chapa
    ): RedirectResponse {
        $this->authorize('update', $chapa);

        $validated = $request->validated();

        $chapa->update($validated);

        return redirect()
            ->route('chapas.edit', $chapa)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Chapa $chapa): RedirectResponse
    {
        $this->authorize('delete', $chapa);

        $chapa->delete();

        return redirect()
            ->route('chapas.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
