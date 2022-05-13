<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\User;

class TransactionController extends Controller
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

    public function dashboard()
    {
       return view('dashboard', ['header' => 'Dashboard', 'user' => auth()->user(), 'desc' => 'You can view all the primary data here']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->user_type != 'admin' && $request->user()->user_type != 'teacher') {
            abort(404);
        }
        $user = $request->user();
        $transactions = $user->transactions();
        $students = User::with('meta.class')->where('user_type', 'student')->get();
        return view('transact', ['header' => 'Transact', 'desc' => 'Add or Edit Transactions', 'students' => $students, 'transactions' => $transactions, 'user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionRequest $request)
    {
        $user = $request->user();
        if ($request->transaction_type == 'expense') {
            $transaction = $user->deposits()->create(
                array_merge($request->except(['reciever_id', 'sender_id']), ['created_by' => $request->user()->id, 'reciever_id' => $request->other_id ])
            );
        }
        else {
            $transaction = $user->expenses()->create(
                array_merge($request->except(['reciever_id', 'sender_id']), ['created_by' => $request->user()->id, 'sender_id' => $request->other_id ])
            );
        }
        return response()->json(['message' => 'Transaction Created Successfully', 'status' => 'success', 'transaction' => $transaction], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTransactionRequest  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
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
}
