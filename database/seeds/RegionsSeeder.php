<?php
use FSR\Region;
use Illuminate\Database\Seeder;

class RegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::create([
          'id' => '1',
          'name' => 'Северен Регион',
        ]);
        Region::create([
          'id' => '2',
          'name' => 'Централен Регион',
        ]);
        Region::create([
          'id' => '3',
          'name' => 'Источен Регион',
        ]);
        Region::create([
          'id' => '4',
          'name' => 'Западен Регион',
        ]);
    }
}
