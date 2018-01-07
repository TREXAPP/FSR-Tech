<?php
use FSR\DonorType;
use Illuminate\Database\Seeder;

class DonorTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DonorType::create([
          'name' => 'Супермаркет',
          'description' => 'Супермаркет',
        ]);
        DonorType::create([
          'name' => 'Кујна',
          'description' => 'Народна кујна',
        ]);
        DonorType::create([
          'name' => 'Бензинска пумпа',
          'description' => '',
        ]);
        DonorType::create([
          'name' => 'Маалска продавничка',
          'description' => '',
        ]);
        DonorType::create([
          'name' => 'Друго',
          'description' => 'Останато',
        ]);
    }
}
