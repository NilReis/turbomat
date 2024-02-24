<?php

namespace App\Http\Controllers;

use App\Models\ReservedItem;
use Illuminate\Http\Request;

class ReservedItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservedItems = ReservedItem::with('chapaItems')->get();
        return view('app.chapas.reserved_items', compact('reservedItems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReservedItem  $reservedItem
     * @return \Illuminate\Http\Response
     */
    public function show(ReservedItem $reservedItem)
    {
        // Carregar relacionamentos adicionais, se necessário, por exemplo:
        $reservedItem->load('chapaItems');
        $chapaItemsCount = $reservedItem->chapaItems->count();
    
        return view('app.chapas.show_reserved_item', compact('reservedItem', 'chapaItemsCount'));
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReservedItem  $reservedItem
     * @return \Illuminate\Http\Response
     */
    public function edit(ReservedItem $reservedItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReservedItem  $reservedItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReservedItem $reservedItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReservedItem  $reservedItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReservedItem $reservedItem)
    {
        $reservedItem->delete();

        return redirect()->route('chapas.itens-reservados.index')->with('success', 'Item deletado com sucesso!');
    }
}
