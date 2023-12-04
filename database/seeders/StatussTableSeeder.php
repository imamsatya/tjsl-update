<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatussTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Reset the table
        DB::table('statuss')->truncate();

        // Insert new data
        DB::table('statuss')->insert([
            
            
            ['nama' => 'Verified', 'keterangan' => null],
            ['nama' => 'In Progress', 'keterangan' => null],
            ['nama' => 'Unfilled', 'keterangan' => null],
            ['nama' => 'Validated', 'keterangan' => null],
           
        ]);
    }
}
