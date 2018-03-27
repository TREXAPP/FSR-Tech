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
        ]);
        Location::create([
          'name' => 'Аеродром',
          'description' => 'Општина Аеродром',
        ]);
        Location::create([
          'name' => 'Илинден',
          'description' => 'Општина Илинден',
        ]);
        Location::create([
          'name' => 'Гази Баба',
          'description' => 'Општина Гази Баба',
        ]);
        Location::create([
          'name' => 'Кисела Вода',
          'description' => 'Општина Кисела Вода',
        ]);
        Location::create([
          'name' => 'Карпош',
          'description' => 'Општина Карпош',
        ]);
        Location::create([
          'name' => 'Ѓорче Петров',
          'description' => 'Општина Ѓорче Петров',
        ]);
        Location::create([
          'name' => 'Велес',
          'description' => 'Општина Велес',
        ]);
        Location::create([
          'name' => 'Куманово',
          'description' => 'Општина Куманово',
        ]);
        Location::create([
          'name' => 'Тетово',
          'description' => 'Општина Тетово',
        ]);
        Location::create([
          'name' => 'Крива Паланка',
          'description' => 'Општина Крива Паланка',
        ]);
        Location::create([
          'name' => 'Кочани',
          'description' => 'Општина Кочани',
        ]);
    }
}
