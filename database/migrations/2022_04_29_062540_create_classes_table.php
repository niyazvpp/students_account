<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Classes;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('fullname');
            $table->foreignId('teacher_id')->nullable();
            $table->timestamps();
        });

        for ($i=1; $i < 11; $i++) {
            Classes::create([
                'name' => 'STD ' . $i,
                'fullname' => 'STD ' . $i
            ]);
        }
        Classes::create([
            'name' => 'Hifz',
            'fullname' => 'Hifz'
        ]);

        Classes::create([
            'name' => 'All',
            'fullname' => 'All'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
    }
}
