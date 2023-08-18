<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
          // Update the existing row with id 1 to change "Finish" to "Completed"
          DB::table('statuses')
          ->where('id', 1)
          ->update(['nama' => 'Completed']);

      // Add a new row with id 4 and "Verified"
      DB::table('statuses')->insert([
          'id' => 4,
          'nama' => 'Verified',
      ]);

      DB::table('statuss')
          ->where('id', 1)
          ->update(['nama' => 'Completed']);

          DB::table('statuss')->insert([
            'id' => 4,
            'nama' => 'Verified',
        ]);
    }
}
