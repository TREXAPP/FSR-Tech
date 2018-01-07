<?php
use FSR\Donor;
use Illuminate\Database\Seeder;

class DonorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Donor::create([
          'email' => 'donor1@donor.mk',
          'password' => '$2y$10$mYGe9IvuUk3ILgd3iw0SfeY4f4JVfn6lDindkio0O1FD.cWWSpWWy', //donor1
          'first_name' => 'Игор',
          'last_name' => 'Јосифов',
          'phone' => '078833227',
          'address' => 'Видое Смилевски Бато 25-2/3',
          'organization_id' => '1',
          'location_id' => '1',
          'donor_type_id' => '1',
          'profile_image_id' => null,
          'notifications' => '1',
          'approved' => '1',
        ]);
    }
}
