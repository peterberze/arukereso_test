<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        Order::truncate();

        $faker = \Faker\Factory::create();

        for($i = 0; $i < 10; $i++) {
            $order = Order::create([
                'customer_name' => $faker->name(),
                'customer_email' => $faker->email(),
                'receipt_type' => 'valami',
                'status' => 'új'
            ]);
            
            $orderNumber = rand(1,3);
            for($j = 1; $j <= $orderNumber; $j++) {
               $orderItem = OrderItem::create([
                'product_name' => $faker->word(),
                'quantity' => $faker->numberBetween(1,10),
                'price' => $faker->numberBetween(100,10000),
                'order_id' => $order->id
               ]); 
            }
            
            $name = $faker->word();
            $zip_code = $faker->postcode();
            $city = $faker->city();
            $street = $faker->streetAddress();

            $address = Address::create([
                'delivery_name' => $name,
                'delivery_zip_code' => $zip_code,
                'delivery_city' => $city,
                'delivery_street' => $street,
                'billing_name' => $name,
                'billing_zip_code' => $zip_code,
                'billing_city' => $city,
                'billing_street' => $street,
                'order_id' => $order->id
            ]);
        }
    }
}
