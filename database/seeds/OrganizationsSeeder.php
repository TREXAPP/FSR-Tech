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
          'id' => '1',
          'name' => 'Tinex',
          'description' => 'Маркети Тинекс, Скопје',
          'type' => 'donor',
          'donor_type_id' => 1
        ]);
        Organization::create([
          'id' => '2',
          'name' => 'Веро маркети',
          'description' => 'Маркети Веро, Скопје',
          'type' => 'donor',
          'donor_type_id' => 4
        ]);
        Organization::create([
          'id' => '3',
          'name' => 'Трекс',
          'description' => 'НВО Трекс, Скопје',
          'type' => 'cso',
          'donor_type_id' => 5
        ]);
        Organization::create([
          'id' => '4',
          'name' => 'Црвен Крст Влае',
          'description' => 'Црвен Крст Влае, Скопје',
          'type' => 'hub',
          'donor_type_id' => 5
        ]);
    }
}
