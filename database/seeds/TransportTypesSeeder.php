<?php
use FSR\TransportType;
use Illuminate\Database\Seeder;

class TransportTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransportType::create([
          'id' => '2',
          'name' => 'пеш',
          'quantity' => 'до 10 кг.',
        ]);
        TransportType::create([
          'id' => '3',
          'name' => 'со автобус',
          'quantity' => 'до 10 кг.',
        ]);
        TransportType::create([
          'id' => '4',
          'name' => 'со велосипед',
          'quantity' => 'до 15 кг.',
        ]);
        TransportType::create([
          'id' => '5',
          'name' => 'со автомобил',
          'quantity' => 'од 20 до 50 кг.',
        ]);
        TransportType::create([
          'id' => '6',
          'name' => 'со комбе',
          'quantity' => 'од 50 до 200 кг.',
        ]);
        TransportType::create([
          'id' => '7',
          'name' => 'со ладилник',
          'quantity' => 'од 50 до 200 кг.',
        ]);
        TransportType::create([
          'id' => '1',
          'name' => 'друго',
          'quantity' => '',
        ]);
    }
}
