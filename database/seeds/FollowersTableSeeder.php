<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users   = User::all();
        $user    = User::find(1);
        $user_id = $user->id;

        //获取去取id为1的数据数组和没有id为1的id数组
        $followers    = $users->slice(1);
        $follower_ids = $followers->pluck('id')->toArray();

        //一号关注其他所有
        $user->follow($follower_ids);

        // 其他所有关注1号
        foreach ($followers as $follower) {
            $follower->follow($user_id);
        }
    }
}
