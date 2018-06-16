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
          'name' => 'resource_page',
          'user_types' => 'cso,donor',
          'description' => 'Се уште не е поставено известување.',
          'comment' => null,
        ]);
    }
}
