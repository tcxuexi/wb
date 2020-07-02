<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(User::class)->times(50)->make();

        User::insert($users->makeVisible(['password', 'remember_token'])->toArray());

        $user           = User::find(1);
        $user->name     = 'xuexi123';
        $user->email    = '1332543018@qq.com';
        $user->password = bcrypt('111111');
        $user->is_admin = true;
        $user->save();
    }
}
