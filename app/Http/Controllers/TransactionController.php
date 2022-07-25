<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Category;
use App\Models\Classes;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{

    public function ajax(Request $request)
    {
        $user = Auth::user();
        Validator::make($request->all(), [
            'data' => 'required|json',
        ])->validate();
        $data = $request->data;
        $data = json_decode($data);
        Validator::make((array) $data, [
            'action' => 'required'
        ])->validate();
        $inputs = $data->inputs ?? (object) [];

        switch ($data->action) {
            case 'transactions':

                $transactions = Transaction::with('sender.meta.class', 'reciever.meta.class');

                if ($user->is('student')) {
                    if (isset($inputs->type) && in_array($inputs->type, ['expenses', 'deposits'])) {
                        $transactions = $user->{$inputs->type}();
                    } else
                        $transactions = $user->transactions();
                    // $transactions = $transactions->where('reciever_id', $user->id)->orWhere('sender_id', $user->id);
                } else {
                    // if input is set, then filter by it
                    if (isset($inputs->user_id)) {
                        if (isset($inputs->type) && in_array($inputs->type, ['expenses', 'deposits'])) {
                            $transactions = $inputs->type == 'deposits' ? $user->expenses() : $user->deposits();
                        } else
                            $transactions = $user->transactions();
                        // $transactions = $transactions->where('reciever_id', $inputs->user_id)->orWhere('sender_id', $inputs->user_id);
                    }

                    if (isset($inputs->search_user)) {
                        // check if input has type and if it is 'expenses' or 'deposits' and filter by it using when function
                        $transaction_type = isset($inputs->type) && in_array($inputs->type, ['expenses', 'deposits']) ? $inputs->type : false;
                        // check if transaction_type is true and if it is 'expenses' or 'deposits' and filter by it using when function
                        $transactions = $transaction_type ? $transactions->when($transaction_type == 'deposits', function ($query) use ($user) {
                            return $query->where('sender_id', $user->id);
                        })->when($transaction_type == 'expenses', function ($query) use ($user) {
                            return $query->where('reciever_id', $user->id);
                        }) : $transactions->where(function ($query) use ($inputs) {
                            $query->where('sender_id', $inputs->search_user)->orWhere('reciever_id', $inputs->search_user);
                        });
                    }

                    // if two user_ids provided filter by common transactions between both users
                    if (isset($inputs->user_id_1) && isset($inputs->user_id_2)) {
                        if (in_array($user->id, [$inputs->user_id_1, $inputs->user_id_2])) {
                            $transactions = $user->transactions();
                        }
                        $transactions = $transactions->where(function ($query) use ($inputs) {
                            $query->where('transactions.reciever_id', $inputs->user_id_1)->orWhere('transactions.reciever_id', $inputs->user_id_2);
                        })->Orwhere(function ($query) use ($inputs) {
                            $query->where('transactions.sender_id', $inputs->user_id_1)->orWhere('transactions.sender_id', $inputs->user_id_2);
                        });
                    }

                    // show transactions by classes
                    if (isset($inputs->class_id)) {
                        $transactions = $transactions
                                ->leftJoin('users as s', 's.id', '=', 'transactions.sender_id')
                                ->leftJoin('users as r', 'r.id', '=', 'transactions.reciever_id')
                                ->leftJoin('students as sm', 'sm.user_id', '=', 's.id')
                                ->leftJoin('students as rm', 'rm.user_id', '=', 'r.id')
                                ->where(function($query) use ($inputs) {
                                    $query->where('sm.class_id', $inputs->class_id)
                                        ->orWhere('rm.class_id', $inputs->class_id);
                                });
                    }
                }

                // if(isset($inputs->user_another)) {
                //     $transactions = $transactions->where(function ($query) use ($inputs) {
                //         $query->where('sender_id', $inputs->user_another)->orWhere('reciever_id', $inputs->user_another);
                //     });
                // }

                if (!empty($inputs->search)) {
                    if (!isset($inputs->class_id)) {
                        $transactions = $transactions->leftJoin('users as s', 's.id', '=', 'transactions.sender_id')
                                                    ->leftJoin('users as r', 'r.id', '=', 'transactions.reciever_id');
                    }
                    $transactions = $transactions->where(function ($query) use ($inputs) {
                                            $query->where('transactions.description', 'like', '%' . $inputs->search . '%')
                                                ->orWhere('transactions.amount', 'like', '%' . $inputs->search . '%')
                                                ->orWhere('transactions.remarks', 'like', '%' . $inputs->search . '%')
                                                ->orWhere('s.name', 'like', '%' . $inputs->search . '%')
                                                ->orWhere('r.name', 'like', '%' . $inputs->search . '%')
                                                ->orWhere('s.username', 'like', '%' . $inputs->search . '%')
                                                ->orWhere('r.username', 'like', '%' . $inputs->search . '%')
                                                ->orWhere('transactions.created_at', 'like', '%' . $inputs->search . '%');
                                        });
                 }

                if (isset($inputs->category_id)) {
                    $transactions = $transactions->where('transactions.category_id', $inputs->category_id);
                }

                // show only transactions on a date range
                if (isset($inputs->date_from) && isset($inputs->date_to)) {
                    $transactions = $transactions->whereBetween('transactions.created_at', [$inputs->date_from, $inputs->date_to]);
                }

                // show only transactions on a date
                if (isset($inputs->date)) {
                    $transactions = $transactions->where('transactions.created_at', 'like', $inputs->date . '%');
                }

                $transactions = $transactions->orderBy('transactions.created_at', 'desc')->select('transactions.*');
                // $query = $transactions;
                // $bindings = $query->getBindings();

                // $query = preg_replace_callback('/\?/', function ($match) use (&$bindings, $query) {
                //     return $query->getConnection()->getPdo()->quote(array_shift($bindings));
                // }, $query->toSql());
                if (!$user->isAdmin() || !isset($inputs->export)) {
                    $transactions = $transactions->paginate($inputs->limit ?? 25);
                } else {
                    $transactions = $transactions->get();
                }
                return response()->json([
                    'transactions' => $transactions,
                    'success' => true
                ], 200);
                break;

            case 'students':

                if ($user->is('student')) {
                    return response()->json(['error' => 'Invalid action'], 422);
                }

                $students = User::with('meta.class');
                if(!$user->isAdmin())
                    $students = $students->where('user_type', 'student');
                if (isset($inputs->search)) {
                    $students = $students->where(function ($query) use ($inputs) {
                            $query->where('name', 'like', '%' . $inputs->search . '%')
                                  ->orWhere('username', 'like', '%' . $inputs->search . '%')
                                  ->orWhere('email', 'like', '%' . $inputs->search . '%');
                    });
                }
                if (isset($inputs->class_id)) {
                    $students = $students->whereHas('meta', function ($query) use ($inputs) {
                        $query->where('class_id', $inputs->class_id);
                    });
                }
                if (isset($inputs->search)) {
                    $s = $inputs->search;
                    $students = $students->orderByRaw("CASE
                    WHEN name LIKE '$s' THEN 1
                    WHEN username LIKE '$s' THEN 2
                    WHEN email LIKE '$s' THEN 3
                    WHEN name LIKE '$s%' THEN 4
                    WHEN username LIKE '$s%' THEN 5
                    WHEN email LIKE '$s%' THEN 6
                    WHEN name LIKE '%$s' THEN 7
                    WHEN username LIKE '%$s' THEN 8
                    WHEN email LIKE '%$s' THEN 9
                    WHEN name LIKE '%$s%' THEN 10
                    WHEN username LIKE '%$s%' THEN 11
                    WHEN email LIKE '%$s%' THEN 12
                    ELSE 12 END", 'asc');
                }

                $students = $students->orderBy('name', 'asc');
                if (!isset($inputs->export)) {
                    $students = $students->limit($inputs->limit ?? 25)->get();
                } else {
                    $students = $students->get();
                }

                return response()->json([
                    'students' => $students,
                    'success' => true
                ], 200);

                break;

                default:
                    return response()->json(['error' => 'Invalid action'], 422);
                break;

        }
    }

    public function index()
    {
        $user = Auth::user();
        $categories = Category::all();
        $header = 'Transactions';
        $desc = 'View Lists of your Transactions!';
        return view('transactions', compact('user', 'categories', 'header', 'desc'));
    }

    public function dashboard()
    {
        $user = auth()->user();
        return view('dashboard', ['header' => 'Dashboard', 'user' => $user, 'desc' => 'You can view all the primary data here']);
    }

    public function create(Request $request)
    {
        $categories = Category::all();
        $classes = [];
        $make = 'Filter';
        if (!$request->user()->is('student')) {
            $classes = Classes::withCount('students')->get();
            $make = 'Make';
        }
        $user = $request->user()->load('meta.class');
        return view('transact', ['header' => 'Transactions', 'desc' => 'View and ' . $make . ' Transactions', 'categories' => $categories, 'user' => $user, 'classes' => $classes]);
    }


    public function store(StoreTransactionRequest $request)
    {
        $user = $request->user();
        $category_new = false;
        if (($category_id = $request->category_id) == 0) {
            $category_new = (new CategoryController)->insertCategory($request->merge(['name' => $request->category_name]));
             $category_id = $category_new->id;
        }
        $ids =  $request->ids ? explode(',', $request->ids) : [];
        if ( !$request->exclude && (!count($ids) || User::whereIn('id', $ids)->count() != count($ids) || in_array($user->id, $ids))) {
            return response()->json(['error' => 'Invalid users'], 422);
        }
        if ($request->exclude) {
            $ids = count($ids) ? User::whereNotIn('id', $ids) : new User;
            if ($request->class_id) {
                $ids = $ids->whereHas('meta', function ($query) use ($request) {
                    $query->where('class_id', $request->class_id);
                });
            }
            $ids = $ids->where('user_type', 'student')->pluck('id')->toArray();
        }
        $extra = 0;
        $amount = $request->amount ?? 0;
        if ($request->transaction_type == 'expense' && $request->divide) {
            $new_amount = ceil($request->amount / count($ids));
            $extra = ($new_amount * count($ids)) - $request->amount;
            $amount = $new_amount;
        }
        $data = [];
        foreach ($ids as $id) {
            $add = array_merge($request->except(['reciever_id', 'sender_id', 'updated_by', 'created_at', 'updated_at', 'remarks']), ['created_by' => $request->user()->id, ($request->transaction_type == 'expense' ? 'reciever_id' : 'sender_id') => $id, 'category_id' => $category_id, 'amount' => $amount]);
            $data[] = $add;
        }
        if ($request->transaction_type == 'expense') {
            $transactions = $user->deposits()->createMany($data);
        }
        else {
            $transactions = $user->expenses()->createMany($data);
        }
        if ($extra) {
            $librarian = User::where('username', 'librarian')->first();
            if (!$librarian) {
                $librarian = User::create([
                    'name' => 'Librarian',
                    'username' => 'librarian',
                    'email' => 'librarian@darulhasanath.com',
                    'password' => Hash::make('librarian@dhic.com5784'),
                    'user_type' => 'teacher',
                ]);
            }
            $library_transaction = $librarian->expenses()->create([
                'amount' => $extra,
                'category_id' => $category_id,
                'created_by' => $request->user()->id,
                'sender_id' => $librarian->id,
                'reciever_id' => $user->id,
                'description' => 'Extra amount for transaction ' . $transactions[0]->id
            ]);
            $transactions[] = $library_transaction;
        }
        // $transactions->load('sender.meta.class', 'reciever.meta.class');
        return response()->json(['message' => 'Transaction Created Successfully', 'status' => 'success', 'category' => $category_new], 201);
    }

    public function ajaxTransactions(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'transactions' => 'required|json',
        ]);

        $count = 0;

        $errors = [];

        foreach (json_decode($request->transactions) as $key => $transaction) {

            $validator = Validator::make((array) $transaction, [
                'amount' => 'required|numeric|min:0.5|max:25000',
                'category_name' => 'required|min:4|max:50',
                'description' => 'nullable|max:255',
                'remarks' => 'nullable|numeric|exists:transactions,id',
                'ad_no' => 'required',
                'transaction_type' => 'required|in:expense,deposit',
            ]);

            if ($validator->fails() || !($transaction_check = User::where('username', $transaction->ad_no)->first())) {
                $errors[$transaction->ad_no] = $validator->errors();
                continue;
            }
            $count++;

            if ($transaction->date) $transaction->created_at = Carbon::parse($transaction->date)->format('Y-m-d H:i:s');

            $category_new = false;
            if (!($category_id = Category::where('name', $transaction->category_name)->value('id'))) {
                $category_new = (new CategoryController)->insertCategory(collect(['name' => $transaction->category_name]));
                $category_id = $category_new->id;
            }
            if ($transaction->transaction_type == 'expense') {
                $user->deposits()->create(
                    array_merge(collect($transaction)->except(['reciever_id', 'sender_id', 'updated_by', 'updated_at'])->toArray(), ['created_by' => $request->user()->id, 'reciever_id' => $transaction_check->id, 'category_id' => $category_id])
                );
            }
            else {
                $user->expenses()->create(
                    array_merge(collect($transaction)->except(['reciever_id', 'sender_id', 'updated_by', 'updated_at'])->toArray(), ['created_by' => $request->user()->id, 'sender_id' => $transaction_check->id, 'category_id' => $category_id])
                );
            }
        }

        if (!$count) {
            return response()->json([
                'status' => 'error',
                'message' => 'No Valid Transaction',
                'errors' => $errors
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => $count . ' transaction records created or updated!',
            'errors' => $errors
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTransactionRequest  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransactionRequest $request)
    {
        $transaction = Transaction::findOrFail($request->id);
        $values = [
            'amount' => $request->amount ?? $transaction->amount,
            'category_id' => $request->category_id ?? $transaction->category_id,
            'description' => $request->description ?? $transaction->description,
            'remarks' => $request->remarks ?? $transaction->remarks,
            'created_at' => Carbon::parse($request->created_at ?? $transaction->created_at)->timestamp,
        ];
        $transaction->update(array_merge($values, ['updated_by' => $request->user()->id]));
        return response()->json(['message' => 'Transaction Updated Successfully', 'status' => 'success', 'transaction' => $transaction], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $transaction = Transaction::findOrFail($request->delete_id);
        $transaction->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Transaction Deleted Successfully',
        ], 200);
    }

    public function export()
    {
        $contents = Transaction::with('sender', 'reciever')->latest()->get()->makeHiddenFieldsVisible()->toJson();
        $name = 'backup/transactions_backup';
        while(Storage::exists($name . '.json')) {
            $name .= '_-_';
        }
        $name .= '.json';
        Storage::put($name, json_encode($contents));
        return Storage::download($name);
    }
}
