<?php
use FSR\Admin;
use FSR\Resource;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Resource::create([
          'id' => '1',
          'name' => 'resource_page_cso',
          'user_types' => 'cso',
          'description' => 'Се уште не е поставено известување за примателите.',
          'comment' => null,
        ]);
        Resource::create([
          'id' => '2',
          'name' => 'resource_page_donor',
          'user_types' => 'donor',
          'description' => 'Се уште не е поставено известување за донаторите.',
          'comment' => null,
        ]);
    }
}
