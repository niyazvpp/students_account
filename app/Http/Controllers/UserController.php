<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'contact', 'loyalty')->get();
        return view('add-user', [ 'users' => $users, 'header' => 'Users', 'desc' => 'Add and or Edit Users As you Wish!' ]);
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
        $inserts = [];
        $updates = [];
        $user_ids = User::pluck('id');
        $data = (array) json_decode($request->data);
        $fillables = [ 'id', 'name', 'email', 'password', 'contact', 'loyalty'];
        foreach($data as $id => $row) {
            $id = str_replace('c', '', $id);
            $id = str_replace('u', '', $id);
            $row->id = $id;
            $row = collect($row)->all();
            $req = 'required';
            $isInsert = true;
            $ruleUnique = 'unique:users,email';
            if (is_numeric($id) && $user_ids->contains($id)) {
                $isInsert = false;
                $req = 'nullable';
                $ruleUnique = Rule::unique('users', 'email')->ignore($id);
            }
            $validator = Validator::make($row, [
                'name' => 'bail|' . $req . '|string|max:255|min:4',
                'email' => ['bail', $req, 'string', 'email', 'max:255', $ruleUnique],
                'password' => ['bail', $req, Rules\Password::defaults()],
                'contact' => 'bail|' . $req . '|numeric|digits:10',
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
            return back()->with(['message' => 'Nothing to update!', 'type' => 'error']);
        }

        if (count($inserts)) {
            User::create($inserts);
        }
        foreach ($updates as $update) {
            User::find($update['id'])->update($update);
        }
        return response()->json([
            'message' => count($updates) . ' User Updated and ' . count($inserts) . ' User Created!',
            'response' => 'success',
        ]);
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
    public function update(Request $request, User $user)
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
        //
    }
}
