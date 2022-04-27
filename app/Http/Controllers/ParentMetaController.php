<?php

namespace App\Http\Controllers;

use App\Models\ParentMeta;
use App\Http\Requests\StoreParentMetaRequest;
use App\Http\Requests\UpdateParentMetaRequest;

class ParentMetaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreParentMetaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreParentMetaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ParentMeta  $parentMeta
     * @return \Illuminate\Http\Response
     */
    public function show(ParentMeta $parentMeta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ParentMeta  $parentMeta
     * @return \Illuminate\Http\Response
     */
    public function edit(ParentMeta $parentMeta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateParentMetaRequest  $request
     * @param  \App\Models\ParentMeta  $parentMeta
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateParentMetaRequest $request, ParentMeta $parentMeta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ParentMeta  $parentMeta
     * @return \Illuminate\Http\Response
     */
    public function destroy(ParentMeta $parentMeta)
    {
        //
    }
}
