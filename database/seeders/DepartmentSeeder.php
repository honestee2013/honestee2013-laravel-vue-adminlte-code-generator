<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
       $departments  = ['Science','Art', 'Commerce', 'Mathematics', 
        'Languages', 'Humanities', 'Business Studies', 
        'Home Economics', 'Technical',  'Guidance & Counselling'];
        foreach($departments as $department){
            DB::table('departments')->insert([
                'name' => $department,
            ]);
        }
    }
}
