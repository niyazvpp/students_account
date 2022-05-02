<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\User;
use App\Http\Requests\StoreclassesRequest;
use App\Http\Requests\UpdateclassesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classes = Classes::with('teacher')->get();
        $header = 'Classes';
        $teachers = User::where('user_type', 'teacher')->with('class')->get();
        $desc = "Add, View and or Edit $header As you Wish!";
        return view('classes', compact('header', 'desc', 'classes', 'teachers'));
    }

    public function editClass(Request $request)
    {
        if ($class = $request->class)
            $class = Classes::findOrFail($class);
        $message = 'Class Added Successfully';
        $status = 201;
        if ($class) {
            $this->updateClass($class, $request);
            $message = 'Class Edited Successfully';
            $status = 200;
        } else
            $this->insertClass($request);
        session()->flash('message', $message);
        return response()->json([
            'status' => 'success'
        ], $status);
    }

    private function insertClass($details)
    {
        $details = collect($details->all());
        Validator::make($details->all(), [
            'name' => 'required|min:5|max:255|unique:classes',
            'fullname' => 'required|min:5|max:255|unique:classes',
            'teacher_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('user_type', 'teacher');
                }),
            ],
        ])->validate();
        return Classes::create($details->all());
    }

    private function updateClass($class, $details)
    {
        $details = collect($details->all());
        Validator::make($details->all(), [
            'name' => ['required', 'min:5', 'max:255', Rule::unique('classes', 'name')->ignore($class)],
            'fullname' => ['required', 'min:5', 'max:255', Rule::unique('classes', 'fullname')->ignore($class)],
            'teacher_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('user_type', 'teacher');
                }),
            ],
        ])->validate();
        return $class->update($details->all());
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
     * @param  \App\Http\Requests\StoreclassesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreclassesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function show(classes $classes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function edit(classes $classes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateclassesRequest  $request
     * @param  \App\Models\classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateclassesRequest $request, classes $classes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\classes  $classes
     * @return \Illuminate\Http\Response
     */
    public function destroy(classes $classes)
    {
        //
    }
}
