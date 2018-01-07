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
          'name' => 'kg',
          'description' => 'килограми',
          'portion_size' => '0.3',
        ]);
        QuantityType::create([
          'name' => 'l',
          'description' => 'литри',
          'portion_size' => '0.24',
        ]);
        QuantityType::create([
          'name' => 'ml',
          'description' => 'милилитри',
          'portion_size' => '240',
        ]);
        QuantityType::create([
          'name' => 't',
          'description' => 'тони',
          'portion_size' => '0.0003',
        ]);
        QuantityType::create([
          'name' => 'mg',
          'description' => 'милиграми',
          'portion_size' => '300',
        ]);
    }
}
