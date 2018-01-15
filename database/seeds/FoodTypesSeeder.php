<?php
use FSR\FoodType;
use Illuminate\Database\Seeder;

class FoodTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FoodType::create([
          'id' => '1',
          'name' => 'Овошје',
          'comment' => '',
          'image_id' => '1001',
        ]);
        FoodType::create([
          'id' => '2',
          'name' => 'Зеленчук',
          'comment' => '',
          'image_id' => '1002',
        ]);
        FoodType::create([
          'id' => '3',
          'name' => 'Млечни производи',
          'comment' => '',
          'image_id' => '1003',
        ]);
        FoodType::create([
          'id' => '4',
          'name' => 'Кондиторски производи',
          'comment' => '',
          'image_id' => '1004',
        ]);
    }
}
