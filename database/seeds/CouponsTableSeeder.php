<?php

use App\Model\Coupon;
use Illuminate\Database\Seeder;

class CouponsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('coupons')->delete();

        \DB::table('coupons')->insert([
        [
            'name' => 'Percentage coupon',
            'description' => 'Numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.',
            'code' => 'PERCENTAGE',
            'amount' => 25,
            'amount_type' => Coupon::PERCENTAGE,
            'quantity' => 25,
            'start_date' => \Carbon\Carbon::now(),
            'end_date' => \Carbon\Carbon::now()->addMonth(),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ],
        [
            'name' => 'Nominal coupon',
            'description' => 'Eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur.',
            'code' => 'NOMINAL',
            'amount' => 10000,
            'amount_type' => Coupon::NOMINAL,
            'quantity' => 50,
            'start_date' => \Carbon\Carbon::now(),
            'end_date' => \Carbon\Carbon::now()->addMonth(),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ],
        [
            'name' => 'Expired coupon',
            'description' => 'Example for expired coupon',
            'code' => 'EXPIRED',
            'amount' => 10000,
            'amount_type' => Coupon::NOMINAL,
            'quantity' => 50,
            'start_date' => \Carbon\Carbon::now()->subMonth(),
            'end_date' => \Carbon\Carbon::now()->subDay(),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]]);
    }
}
