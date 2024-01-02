<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'username' => 'didi.rosidi',
                'email' => 'didi.rosidi@bumn.go.id',
                'name' => 'Didi Rosidi',
                'handphone' => '0',
                'kategori_user_id' => 1,
                'kategori_user' => 'Internal KBUMN',
                'source' => 'AD',
                'id_bumn' => null,
                'id_angka' => null,
                'id_huruf' => null,
                'bumn_lengkap' => null,
                'bumn_singkat' => null,
                'asal_instansi' => null,
                'created_by' => 'Seeder'
            ],        
            [
                'username' => 'm.erwin',
                'email' => 'm.erwin@bumn.go.id',
                'name' => 'M.Erwin',
                'handphone' => '0',
                'kategori_user_id' => 1,
                'kategori_user' => 'Internal KBUMN',
                'source' => 'AD',
                'id_bumn' => null,
                'id_angka' => null,
                'id_huruf' => null,
                'bumn_lengkap' => null,
                'bumn_singkat' => null,
                'asal_instansi' => null,
                'created_by' => 'Seeder'
            ]
         ];

        foreach($data as $val){
            User::updateOrCreate($val, $val);
        }
    }
}
