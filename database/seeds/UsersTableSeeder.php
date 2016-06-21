<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = 'Bagaskara Wisnu Gunawan';
        $user->username = 'bagaskara';
        $user->password = bcrypt('bagaskara');
        $user->email = 'me@bagaskara.id';
        $user->save();

        $user = new User;
        $user->name = 'PT. Turboly Teknologi Indonesia';
        $user->username = 'turboly';
        $user->password = bcrypt('turboly');
        $user->email = 'admin@turboly.com';
        $user->save();
    }
}
