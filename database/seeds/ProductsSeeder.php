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
        'id' => '1',
        'name' => 'Банани',
        'description' => '',
        'food_type_id' => '1',
      ]);
        Product::create([
        'id' => '2',
        'name' => 'Портокали',
        'description' => '',
        'food_type_id' => '1',
      ]);
        Product::create([
        'id' => '3',
        'name' => 'Јаболки',
        'description' => '',
        'food_type_id' => '1',
      ]);
        Product::create([
        'id' => '4',
        'name' => 'Круши',
        'description' => '',
        'food_type_id' => '1',
      ]);
        Product::create([
        'id' => '5',
        'name' => 'Кајсии',
        'description' => '',
        'food_type_id' => '1',
      ]);
        Product::create([
        'id' => '6',
        'name' => 'Манго',
        'description' => '',
        'food_type_id' => '1',
      ]);
        Product::create([
        'id' => '7',
        'name' => 'Сливи',
        'description' => '',
        'food_type_id' => '1',
      ]);
        Product::create([
        'id' => '8',
        'name' => 'Краставици',
        'description' => '',
        'food_type_id' => '2',
      ]);
        Product::create([
        'id' => '9',
        'name' => 'Патлиџани',
        'description' => '',
        'food_type_id' => '2',
      ]);
        Product::create([
        'id' => '10',
        'name' => 'Зелки',
        'description' => '',
        'food_type_id' => '2',
      ]);
        Product::create([
        'id' => '11',
        'name' => 'Марула',
        'description' => '',
        'food_type_id' => '2',
      ]);
        Product::create([
        'id' => '12',
        'name' => 'Цвекло',
        'description' => '',
        'food_type_id' => '2',
      ]);
        Product::create([
        'id' => '13',
        'name' => 'Млеко',
        'description' => '',
        'food_type_id' => '3',
      ]);
        Product::create([
        'id' => '14',
        'name' => 'Сирење',
        'description' => '',
        'food_type_id' => '3',
      ]);
        Product::create([
        'id' => '15',
        'name' => 'Чоколади',
        'description' => '',
        'food_type_id' => '4',
      ]);
        Product::create([
        'id' => '16',
        'name' => 'Чипс',
        'description' => '',
        'food_type_id' => '4',
      ]);
        Product::create([
        'id' => '17',
        'name' => 'Бонбони',
        'description' => '',
        'food_type_id' => '4',
      ]);
    }
}
