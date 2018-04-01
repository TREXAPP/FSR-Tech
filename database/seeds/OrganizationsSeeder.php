<?php
use FSR\Organization;
use Illuminate\Database\Seeder;

class OrganizationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Organization::create([
          'name' => 'Tinex',
          'description' => 'Маркети Тинекс, Скопје',
          'type' => 'donor',
          'donor_type_id' => 1
        ]);
        Organization::create([
          'name' => 'Веро маркети',
          'description' => 'Маркети Веро, Скопје',
          'type' => 'donor',
          'donor_type_id' => 4
        ]);
        Organization::create([
          'name' => 'Црвен Крст',
          'description' => 'Црвен крст, Скопје',
          'type' => 'cso',
          'donor_type_id' => 4
        ]);
        Organization::create([
          'name' => 'Трекс',
          'description' => 'НВО Трекс, Скопје',
          'type' => 'cso',
          'donor_type_id' => 5
        ]);
    }
}
