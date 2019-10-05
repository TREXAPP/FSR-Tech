<?php
use FSR\Location;
use Illuminate\Database\Seeder;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::create([
          'name' => 'Центар',
          'description' => 'Општина Центар',
          'region_id' => '1',
        ]);
        Location::create([
          'name' => 'Аеродром',
          'description' => 'Општина Аеродром',
          'region_id' => '1',
        ]);
        Location::create([
          'name' => 'Илинден',
          'description' => 'Општина Илинден',
          'region_id' => '1',
        ]);
        Location::create([
          'name' => 'Гази Баба',
          'description' => 'Општина Гази Баба',
          'region_id' => '1',
        ]);
        Location::create([
          'name' => 'Кисела Вода',
          'description' => 'Општина Кисела Вода',
          'region_id' => '1',
        ]);
        Location::create([
          'name' => 'Карпош',
          'description' => 'Општина Карпош',
          'region_id' => '1',
        ]);
        Location::create([
          'name' => 'Ѓорче Петров',
          'description' => 'Општина Ѓорче Петров',
          'region_id' => '1',
        ]);
        Location::create([
          'name' => 'Велес',
          'description' => 'Општина Велес',
          'region_id' => '2',
        ]);
        Location::create([
          'name' => 'Куманово',
          'description' => 'Општина Куманово',
          'region_id' => '1',
        ]);
        Location::create([
          'name' => 'Тетово',
          'description' => 'Општина Тетово',
          'region_id' => '4',
        ]);
        Location::create([
          'name' => 'Крива Паланка',
          'description' => 'Општина Крива Паланка',
          'region_id' => '3',
        ]);
        Location::create([
          'name' => 'Кочани',
          'description' => 'Општина Кочани',
          'region_id' => '3',
        ]);
    }
}
