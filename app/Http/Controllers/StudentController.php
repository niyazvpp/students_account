<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Category;
use App\Models\Classes;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class StudentController extends Controller
{

    public function ajax(Request $request)
    {
        $user = Auth::user();
        $data = $request->data;
        Validator::make($request->all(), [
            'data' => 'required|json',
        ])->validate();
        $data = json_decode($data);
        Validator::make($data, [
            'action' => 'required'
        ])->validate();
        $inputs = $data->inputs;

        // switch ($data->action) {
        //     case 'students':
    }


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

    public function truncate(Request $request)
    {
        if ($request->user()->user_type != 'admin') {
            abort(404);
        }
        User::where('user_type', 'student')->delete();
        Student::truncate();
        return back()->with('message', 'All Students Deleted!');
    }

    public function ajaxStudents(Request $request)
    {
        if ($request->user()->user_type != 'admin' || !$request->wantsJson()) {
            abort(404);
        }

        $request->validate([
            'students' => 'required|json',
        ]);

        $count = 0;

        $errors = [];

        $upserts = [];
        $upserts_students = [];

        $students_adnos = array_map(function ($student)
        {
            return $student->ad_no;
        }, json_decode($request->students));
        $uniques = User::whereIn('username', $students_adnos)->where('user_type', 'student')->get(['id', 'username']);
        $ad_nos = array_map(function ($student)
        {
            return $student['username'];
        }, $uniques->toArray());

        DB::transaction(function() use ($request, &$count, &$errors, &$upserts, &$upserts_students, &$ad_nos, &$uniques) {
            foreach (json_decode($request->students) as $key => $student) {

                $validator = Validator::make((array) $student, [
                    'name' => 'required|min:2|max:255',
                    'old_balance' => 'nullable|numeric',
                    'batch_id' => 'nullable|string|in:a',
                    'ad_no' => 'required|min:2',
                    'class_id' => 'required|exists:classes,id',
                ]);

                if ($validator->fails()) {
                    $errors[$student->ad_no] = $validator->errors();
                    continue;
                }

                $count++;

                if (in_array($student->ad_no, $ad_nos)) {
                    $upserts[] = [

























































































































































                    
                        'name' => $student->name,
                        'username' => $student->ad_no,
                        'old_balance' => $student->old_balance ?? 0,
                        'password' => Hash::make('student' . $student->ad_no),
                        'user_type' => 'student',
                    ];

                    $upserts_students[] = [
                        'user_id' => $uniques->firstWhere('username', $student->ad_no)->id,
                        'class_id' => $student->class_id,
                        'batch_id' => $student->batch_id ?? null,
                        'ad_no' => $student->ad_no,
                        'dob' => $student->dob ? Carbon::parse($student->dob) : null,
                    ];
                    continue;
                }

                $user = User::create([
                    'name' => $student->name,
                    'username' => $student->ad_no,
                    'old_balance' => $student->old_balance,
                    'password' => Hash::make('student' . $student->ad_no),
                    'user_type' => 'student',
                ]);

                event(new Registered($user));

                Student::create([
                    'user_id' => $user->id,
                    'class_id' => $student->class_id,
                    'ad_no' => $student->ad_no,
                    'dob' => $student->dob ? Carbon::parse($student->dob) : null,
                ]);
            }
        });

        if (!$count) {
            return response()->json([
                'status' => 'error',
                'message' => 'No Valid Student',
                'errors' => $errors
            ], 422);
        }

        if (count($upserts)) {
            User::upsert($upserts, ['username']);
            Student::upsert($upserts_students, ['ad_no']);
        }

        return response()->json([
            'status' => 'success',
            'message' => $count . ' students created or updated!',
            'errors' => $errors
        ], 201);
    }

    public function backup()
    {
        $contents = [];
        $contents['students'] = Student::all()->makeHiddenFieldsVisible(Student::class);
        $contents['transactions'] = Transaction::all()->makeHiddenFieldsVisible(Transaction::class);
        $contents['users'] = User::all()->makeHiddenFieldsVisible(User::class);
        $contents['classes'] = Classes::all()->makeHiddenFieldsVisible(Classes::class);
        $contents['categories'] = Category::all()->makeHiddenFieldsVisible(Category::class);

        $name = 'backup/backup_json';
        while(Storage::exists($name . '.json')) {
            $name .= '_-_';
        }
        $name .= '.json';
        Storage::put($name, json_encode($contents));
        return $name;
    }

    public function export(Request $request)
    {
        if ($request->user()->user_type != 'admin') {
            abort(404);
        }

        $name = $this->backup();
        return Storage::download($name);
    }

    public function import(Request $request)
    {
        $request->validate([
            'json' => 'required|file|mimetypes:application/json',
        ]);
        (new UserController)->artisan($request, false);

        $json = (array) json_decode(file_get_contents($_FILES['json']['tmp_name']));
        $count = 0;
        foreach ($json as $key => $value) {
            if (in_array($key, ['students', 'transactions', 'users', 'classes'])) {
                $model = 'App\\Models\\';
                $model .= $key == 'classes' ? 'Classes' : substr(ucfirst($key), 0, -1);
                foreach ($value as $item) {
                    if ($key == 'users') {
                        $item->password = Hash::make('adminhasanath123');
                    }
                    if (!$model::find($item->id)) {
                        $model::create((array) $item);
                    }
                    $count++;
                }
            }
        }
        return back()->with('message', $count . ' items imported!');

    }
}
