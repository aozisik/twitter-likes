<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'shveddy@gmail.com',
            'password' => bcrypt('123456'),
            'name' => 'Aaron Cederberg'
        ]);
    }
}
