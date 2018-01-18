<?php
use FSR\QuantityType;
use Illuminate\Database\Seeder;

class QuantityTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuantityType::create([
          'id' => '1',
          'name' => 'kg',
          'description' => 'килограми',
        ]);
        QuantityType::create([
          'id' => '2',
          'name' => 'l',
          'description' => 'литри',
        ]);
        QuantityType::create([
          'id' => '3',
          'name' => 'ml',
          'description' => 'милилитри',
        ]);
        QuantityType::create([
          'id' => '4',
          'name' => 't',
          'description' => 'тони',
        ]);
        QuantityType::create([
          'id' => '5',
          'name' => 'mg',
          'description' => 'милиграми',
        ]);
    }
}
