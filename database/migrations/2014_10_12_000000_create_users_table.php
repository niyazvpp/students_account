<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->nullable();
            $table->string('user_type')->nullable();
            $table->bigInteger('mobile')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->bigInteger('old_balance')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });

        $user = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@darulhasanath.com',
            'password' => Hash::make('adminhasanath'),
            'user_type' => 'admin',
            'mobile' => 9999997778,
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
