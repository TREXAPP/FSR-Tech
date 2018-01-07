<?php
use FSR\Cso;
use Illuminate\Database\Seeder;

class CsosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cso::create([
          'email' => 'cso1@cso.mk',
          'password' => '$2y$10$VTkMD9uOZHYCqqlFg5Y.q.5Us6GOp93ieJW/SdNmTtR.JiP739Whu', //csocso
          'first_name' => 'Игор',
          'last_name' => 'Пирковски',
          'phone' => '071234567',
          'address' => 'Женевска 34/6',
          'organization_id' => '3',
          'location_id' => '2',
          'profile_image_id' => null,
          'notifications' => '1',
          'approved' => '1',
        ]);
    }
}
