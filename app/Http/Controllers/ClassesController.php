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
    public function index(Request $request)
    {
        $classes = Classes::with('teacher')->get();
        $header = 'Classes';
        $teachers = User::where('user_type', 'teacher')->with('class')->get();
        $desc = "Add, View and or Edit $header As you Wish!";
        $user = $request->user();
        return view('classes', compact('header', 'desc', 'classes', 'teachers', 'user'));
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
            'name' => 'required|min:4|max:255|unique:classes',
            'fullname' => 'required|min:4|max:255|unique:classes',
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
}
