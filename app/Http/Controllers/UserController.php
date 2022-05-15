<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classes;
use App\Models\Student;
use Illuminate\Support\Facades\Artisan;
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
        $users = User::select('id', 'name', 'email', 'mobile')->get();
        return view('add-user', [ 'users' => $users, 'header' => 'Users', 'desc' => 'Add and or Edit Users As you Wish!' ]);
    }

    public function parents()
    {
        $usersData = User::where('user_type', 'parent')->with(['students.user'])->get();
        $users = User::where('user_type', 'parent')->with(['students.user'])->paginate(25);
        $header = 'Parents';
        $desc = 'Add, View and or Edit Parent Wisely!';
        $students = Student::with(['user', 'class'])->get();
        return view('parents', compact('header', 'desc', 'users', 'usersData', 'students'));
    }

    public function editParent(Request $request)
    {
        $parent = User::findOrFail($request->id);
        $ids = explode(',', $request->ids);
        $students = User::whereIn('id', $ids)->where('user_type', 'student')->with('meta.parent')->get();

        if (!count($students)) {
            session()->flash('type', 'error');
            $message = 'Atlease One Student is required!';
        }

        foreach($students as $student) {
            $parentOf = $student->meta->parent;
            if (!$parentOf || $parentOf->id != $request->id) {
                if ($parentOf && count($parentOf->students) == 1) {
                    $parentOf->delete();
                }
                $student->meta->parent_id = $request->id;
                $student->meta->save();
            }
        }

        session()->flash('message', $message ?? 'Parent Students Updated!');
        return response()->json([
            'status' => 'success',
        ], 200);
    }

    public function editTeacher(Request $request, User $user = null)
    {
        $teacher = $this->editUser($request, $user, 'teacher');
        return $teacher;
    }

    public function users($type)
    {
        if (!in_array($type, ['teachers'])) {
            abort(404);
        }
        $type = substr_replace($type ,"", -1);
        $usersData = User::where('user_type', $type)->with('class')->get();

        $users = User::where('user_type', $type);
        if ($type != 'teacher')
            $users = $users->paginate(25);
        else
            $users = $users->with('class')->get();

        $header = ucfirst($type . 's');
        $desc = "Add, View and or Edit $header As you Wish!";
        $classes = Classes::with('teacher')->get();
        return view('teachers', compact('header', 'desc', 'type', 'users', 'usersData', 'classes'));
    }

    public function editUser(Request $request, $user_type)
    {
        if (!in_array($user_type, ['teachers'])) {
            abort(404);
        }
        if ($user = $request->user)
            $user = User::findOrFail($user);
        $user_type = substr_replace($user_type ,"", -1);
        $message = ucfirst($user_type) . ' Added Successfully';
        $status = 201;
        if ($user) {
            $user = $this->updateUser($user, $request, $user_type);
            $message = ucfirst($user_type) . ' Edited Successfully';
            $status = 200;
        } else
            $user = $this->insertUser($request, $user_type);

        if ($user_type == 'teacher' && $request->class) {
            $request->validate([
                'class' => 'required|exists:classes,id'
            ]);
            if ($status == 200 && ($class = $user->class)) {
                $class->teacher_id = null;
                $class->save();
            }
            $class = Classes::find($request->class);
            $class->teacher_id = $user->id;
            $class->save();
        }
        session()->flash('message', $message);
        return response()->json([
            'status' => 'success'
        ], $status);
    }

    private function insertUser($details, $user_type)
    {
        $details = collect($details->all());
        Validator::make($details->all(), [
            'name' => 'required|min:5|max:255',
            'email' => 'required|email|max:255',
            'username' => 'required|min:5|unique:users,username',
            'mobile' => 'required|numeric|digits:10|unique:users,mobile',
            'old_balance' => 'nullable|numeric|min:0',
            'password' => ['required', Rules\Password::defaults()],
        ])->validate();
        if ($user_type == 'parent') {
            $details->put('old_balance', 0);
        }
        $details->put('password', Hash::make($details->get('password')));
        $details->put('user_type', $user_type);
        return User::create($details->all());
    }

    private function updateUser(User $user, $details, $user_type)
    {
        $details = collect($details->all());
        Validator::make($details->all(), [
            'name' => 'required|min:5|max:255',
            'email' => 'required|email|max:255',
            'username' => ['required','min:5', Rule::unique('users')->ignore($user)],
            'mobile' => ['required', 'numeric', 'digits:10', Rule::unique('users')->ignore($user)],
            'old_balance' => 'required|numeric|min:0',
            'password' => ['nullable', Rules\Password::defaults()],
        ])->validate();
        if ($user_type == 'parent') {
            $details->put('old_balance', 0);
        }
        if (!empty($details->get('password')))
            $details->put('password', Hash::make($details->get('password')));
        else $details->forget('password');

        $user->update($details->all());
        return $user;
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

    public function teacher(User $teacher)
    {
        $teacher->load('class');
        return view('teacher', compact('teacher'));
    }

    public function artisan(Request $request)
    {
        (new StudentController)->backup();

        Artisan::call('migrate:refresh --seed --force');
        return redirect('dashboard')->with(['message' => 'Migration Successful!', 'type' => 'success']);
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
