<?php
use FSR\Product;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
        'name' => 'Банани',
        'description' => '',
        'food_type_id' => '1',
      ]);
        Product::create([
        'name' => 'Портокали',
        'description' => '',
        'food_type_id' => '1',
      ]);
        Product::create([
        'name' => 'Јаболки',
        'description' => '',
        'food_type_id' => '1',
      ]);
        Product::create([
        'name' => 'Круши',
        'description' => '',
        'food_type_id' => '1',
      ]);
        Product::create([
        'name' => 'Кајсии',
        'description' => '',
        'food_type_id' => '1',
      ]);
        Product::create([
        'name' => 'Манго',
        'description' => '',
        'food_type_id' => '1',
      ]);
        Product::create([
        'name' => 'Сливи',
        'description' => '',
        'food_type_id' => '1',
      ]);
        Product::create([
        'name' => 'Краставици',
        'description' => '',
        'food_type_id' => '2',
      ]);
        Product::create([
        'name' => 'Патлиџани',
        'description' => '',
        'food_type_id' => '2',
      ]);
        Product::create([
        'name' => 'Зелки',
        'description' => '',
        'food_type_id' => '2',
      ]);
        Product::create([
        'name' => 'Марула',
        'description' => '',
        'food_type_id' => '2',
      ]);
        Product::create([
        'name' => 'Цвекло',
        'description' => '',
        'food_type_id' => '2',
      ]);
        Product::create([
        'name' => 'Млеко',
        'description' => '',
        'food_type_id' => '3',
      ]);
        Product::create([
        'name' => 'Сирење',
        'description' => '',
        'food_type_id' => '3',
      ]);
        Product::create([
        'name' => 'Чоколади',
        'description' => '',
        'food_type_id' => '4',
      ]);
        Product::create([
        'name' => 'Чипс',
        'description' => '',
        'food_type_id' => '4',
      ]);
        Product::create([
        'name' => 'Бонбони',
        'description' => '',
        'food_type_id' => '4',
      ]);
    }
}
