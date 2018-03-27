<?php
use FSR\Volunteer;
use Illuminate\Database\Seeder;

class VolunteersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Volunteer::create([
          'id' => '1',
          'first_name' => 'Игор',
          'last_name' => 'Пирковски',
          'email' => 'cso1@cso.mk',
          'phone' => '071234567',
          'image_id' => null,
          'organization_id' => '3',
          'added_by_user_id' => '1',
          'status' => 'active',
          'is_user' => '1',
        ]);
    }
}
