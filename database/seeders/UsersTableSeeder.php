<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            'first_name'        => 'System',
            'last_name'         => 'Admin',
            'email'             => 'admin@bookingcore.test',
            'password'          => bcrypt('admin123'),
            'phone'             => '112 666 888',
            'status'            => 'publish',
            'city'            => 'New York',
            'country'            => 'US',
            'created_at'        => date("Y-m-d H:i:s"),
            'email_verified_at' => date("Y-m-d H:i:s"),
            'bio'               => 'We\'re designers who have fallen in love with creating spaces for others to reflect, reset, and create. We split our time between two deserts (the Mojave, and the Sonoran). We love the way the heat sinks into our bones, the vibrant sunsets, and the wildlife we get to call our neighbors.'
        ]);
        $user = \App\User::where('email', 'admin@bookingcore.test')->first();
        if(!is_demo_mode()){
            $user->need_update_pw = 1;
            $user->save();
        }

        $user->assignRole('administrator');
        DB::table('users')->insert([
            'first_name'        => 'Vendor',
            'last_name'         => '01',
            'email'             => 'vendor1@bookingcore.test',
            'password'          => bcrypt('123456Aa'),
            'phone'             => '112 666 888',
            'status'            => 'publish',
            'city'            => 'New York',
            'country'            => 'US',
            'created_at'        => date("Y-m-d H:i:s"),
            'email_verified_at' => date("Y-m-d H:i:s"),
            'bio'               => 'We\'re designers who have fallen in love with creating spaces for others to reflect, reset, and create. We split our time between two deserts (the Mojave, and the Sonoran). We love the way the heat sinks into our bones, the vibrant sunsets, and the wildlife we get to call our neighbors.'
        ]);
        $user = \App\User::where('email', 'vendor1@bookingcore.test')->first();
        $user->need_update_pw = 1;
        $user->save();
        $user->assignRole('vendor');

        DB::table('users')->insert([
            'first_name'        => 'Customer',
            'last_name'         => '01',
            'email'             => 'customer1@bookingcore.test',
            'password'          => bcrypt('123456Aa'),
            'phone'             => '112 666 888',
            'status'            => 'publish',
            'city'            => 'New York',
            'country'            => 'US',
            'created_at'        => date("Y-m-d H:i:s"),
            'email_verified_at' => date("Y-m-d H:i:s"),
            'bio'               => 'We\'re designers who have fallen in love with creating spaces for others to reflect, reset, and create. We split our time between two deserts (the Mojave, and the Sonoran). We love the way the heat sinks into our bones, the vibrant sunsets, and the wildlife we get to call our neighbors.'
        ]);
        $user = \App\User::where('email', 'customer1@bookingcore.test')->first();
        $user->assignRole('customer');
        if(!is_demo_mode()){
            $user->need_update_pw = 1;
            $user->save();
        }

        $vendor = [
            [
                'Elise',
                'Aarohi'
            ],
            [
                'Kaytlyn',
                'Alvapriya'
            ],
            [
                'Lynne',
                'Victoria'
            ]
        ];
        foreach ($vendor as $k => $v) {
            DB::table('users')->insert([
                'first_name'        => $v[0],
                'last_name'         => $v[1],
                'email'             => strtolower($v[1]) . '@bookingcore.test',
                'password'          => bcrypt('123456Aa'),
                'phone'             => '112 666 888',
                'status'            => 'publish',
                'city'            => 'New York',
                'country'            => 'US',
                'created_at'        => date("Y-m-d H:i:s"),
                'email_verified_at' => date("Y-m-d H:i:s"),
                'bio'               => 'We\'re designers who have fallen in love with creating spaces for others to reflect, reset, and create. We split our time between two deserts (the Mojave, and the Sonoran). We love the way the heat sinks into our bones, the vibrant sunsets, and the wildlife we get to call our neighbors.'
            ]);
            $user = \App\User::where('email', $v[1] . '@bookingcore.test')->first();
            if(!is_demo_mode()){
                $user->need_update_pw = 1;
                $user->save();
            }
            $user->assignRole('vendor');
        }
    }
}
