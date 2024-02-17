<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChapaItem; // Assumindo que você tem um Model chamado ChapaItem


class ChapaItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chapaItems = ChapaItem::all();
        return view('app.chapas.chapasitem.chapa_items', compact('chapaItems'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $chapaItem = ChapaItem::findOrFail($id);
        $chapaItem->delete();

        // Redirecione para a lista de itens com uma mensagem de sucesso
        return redirect()->route('chapa-items.index')->with('success', 'Item deletado com sucesso!');
    }


    public function search(Request $request)
    {

        $searchId = $request->input('searchId');

        // Supondo que você tenha um modelo ChapaItem
        $chapaItems = ChapaItem::where('id', $searchId)->get();


        return view('app.chapas.chapasitem.chapa_items', compact('chapaItems'));
    }
}
