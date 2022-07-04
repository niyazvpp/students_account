<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@darulhasanath.com',
            'password' => Hash::make('adminhasanath'),
            'user_type' => 'admin',
            'mobile' => 9605610310,
        ]);

        event(new Registered($user));

        for ($i=1; $i < 11; $i++) {
            $user = User::create([
                'name' => 'Class Teacher ' . $i,
                'username' => 'classteacher' . $i,
                'password' => Hash::make('classteacher' . $i),
                'user_type' => 'teacher',
            ]);

            event(new Registered($user));
        }

        $user = User::create([
            'name' => 'Class Teacher Hifz',
            'username' => 'classteacherhifz',
            'password' => Hash::make('classteacherhifz'),
            'user_type' => 'teacher',
        ]);

        event(new Registered($user));
        $this->call(ClassesSeeder::class);
    }
}
