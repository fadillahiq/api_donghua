<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Muhamad Iqbal Fadilah',
            'email' => 'laku0505@gmail.com',
            'password' => bcrypt('Market22')
        ]);
    }
}
