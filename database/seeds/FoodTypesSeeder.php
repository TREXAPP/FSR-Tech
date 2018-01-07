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
          'default_image' => '../img/food_types/fruits.jpg',
        ]);
        FoodType::create([
          'id' => '2',
          'name' => 'Зеленчук',
          'comment' => '',
          'default_image' => '../img/food_types/vegetables.jpg',
        ]);
        FoodType::create([
          'id' => '3',
          'name' => 'Млечни производи',
          'comment' => '',
          'default_image' => '../img/food_types/dairy.jpg',
        ]);
        FoodType::create([
          'id' => '4',
          'name' => 'Кондиторски производи',
          'comment' => '',
          'default_image' => '../img/food_types/conditory.jpg',
        ]);
    }
}
