<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shops = Shop::select('id', 'name', 'address', 'loyalty')->get();
        return view('shops', [ 'shops' => $shops, 'header' => 'Shops', 'desc' => 'Add and or Edit shops As you Wish!' ]);
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
     * @param  \App\Http\Requests\StoreShopRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inserts = [];
        $updates = [];
        $user_ids = Shop::pluck('id');
        $data = (array) json_decode($request->data);
        $fillables = [ 'id', 'name', 'address', 'loyalty'];
        foreach($data as $id => $row) {
            $id = str_replace('c', '', $id);
            $id = str_replace('u', '', $id);
            $row->id = $id;
            $row = collect($row)->all();
            $req = 'required';
            $isInsert = true;
            if (is_numeric($id) && $user_ids->contains($id)) {
                $isInsert = false;
                $req = 'nullable';
            }
            $validator = Validator::make($row, [
                'name' => 'bail|' . $req . '|string|max:255|min:4',
                'address' => 'bail|' . $req . '|string|min:8',
                'loyalty' => 'bail|' . $req . '|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'response' => 'error',
                ]);
            }

            $row = array_filter($row, function($value, $key) use ($fillables) {
                return in_array($key, $fillables);
            }, ARRAY_FILTER_USE_BOTH);

            if ($isInsert) {
                $inserts[] = $row;
            }
            else {
                $updates[] = $row;
            }
        }

        if (!count($data)) {
            return response()->json([
                'errors' => json_decode(json_encode(['error' => 'No Data Submitted!'])),
                'response' => 'error',
            ]);
        }

        if (count($inserts)) {
            Shop::create($inserts);
        }
        foreach ($updates as $update) {
            Shop::find($update['id'])->update($update);
        }
        return response()->json([
            'message' => count($updates) . ' Shop Updated and ' . count($inserts) . ' Shop Created!',
            'response' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function show(Shop $shop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function edit(Shop $shop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateShopRequest  $request
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShopRequest $request, Shop $shop)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shop $shop)
    {
        //
    }
}
