<?php

namespace Database\Seeders;

use App\Models\Classes;
use Illuminate\Database\Seeder;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
}
