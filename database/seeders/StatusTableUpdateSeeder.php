<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class StatusTableUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Update status with ID 1 to 'Verified'
        DB::table('statuses')
            ->where('id', 1)
            ->update(['nama' => 'Verified']);

        // Update status with ID 4 to 'Validated'
        DB::table('statuses')
            ->where('id', 4)
            ->update(['nama' => 'Validated']);

        // Update status with ID 1 to 'Verified'
        DB::table('statuss')
            ->where('id', 1)
            ->update(['nama' => 'Verified']);

        // Update status with ID 4 to 'Validated'
        DB::table('statuss')
            ->where('id', 4)
            ->update(['nama' => 'Validated']);

        
    }
}
