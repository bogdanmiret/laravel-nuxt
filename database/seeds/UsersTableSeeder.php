<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Bogdan Miret',
            'email' => 'miretbogdan@gmail.com',
            'password' => Hash::make(('bogdan')),
        ]);

        $user->assignRole('super-admin');
    }
}
