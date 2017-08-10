<?php

use App\Model\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();

        \DB::table('users')->insert([
        [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('secret'),
            'group_type' => User::ADMIN,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ],
        [
            'name' => 'Customer 1',
            'email' => 'customer1@customer.com',
            'password' => bcrypt('secret'),
            'group_type' => User::CUSTOMER,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ],
        [
            'name' => 'Customer 2',
            'email' => 'customer2@customer.com',
            'password' => bcrypt('secret'),
            'group_type' => User::CUSTOMER,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]]);
    }
}
