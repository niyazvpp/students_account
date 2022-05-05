<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // show all users with usertype student with student meta
        $students = Student::with(['user', 'class'])->get();
        return view('students', compact('students'));
    }

    public function generateParents()
    {
        $students = Student::whereNull('parent_id')->get();
        if (!count($students)) {
            return back()->with(['message' => 'No Student without Parent Created', 'type' => 'error']);
        }
        foreach ($students as $c => $student) {
            $parent = (new UserController)->addUser(collect([
                'name' => 'Parent of ' . $student->user->name,
                'email' => $student->user->email,
                'username' => $student->user->mobile,
                'mobile' => $student->user->mobile,
                'password' => $student->user->dob->format('d.m.Y'),
            ]));
            $student->parent_id = $parent->id;
            $student->save();
        }
        $c = $c + 1;

        return back()->with([ 'message' => "$c new parent(s) generated", 'type' => 'success' ]);
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
     * @param  \App\Http\Requests\StoreStudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request)
    {
        $request->validate([
            'name' => 'required|min:5|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|numeric|digits:10|unique:users,mobile',
            'old_balance' => 'nullable|numeric|min:0',
            'password' => ['required', Password::defaults()],
            // add student meta
        ]);
        $request->put('password', Hash::make($request->password));
        $request->put('user_type', 'student');
        return User::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStudentRequest  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
