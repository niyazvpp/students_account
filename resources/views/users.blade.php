@extends('layouts.dash')

@section('main')

<div class="">
   <div class="w-full">

        @php

        $headers = ['Id', 'Name', 'Email', 'Contact', 'Loyalty'];
        $body = [];
        foreach ($users as $user) {
            $body[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->contact,
                $user->loyalty
            ];
        }

        @endphp

        <div class="card">
            <div class="card-body">
                <x-table :headers="$headers" :body="$body" />
            </div>
        </div>

   </div>
</div>

@endsection
