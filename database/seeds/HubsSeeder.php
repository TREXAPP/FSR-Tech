<?php
use FSR\Hub;
use Illuminate\Database\Seeder;

class HubsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Hub::create([
          'id' => '1',
          'email' => 'hub1@hub.mk',
          'password' => '$2y$10$VTkMD9uOZHYCqqlFg5Y.q.5Us6GOp93ieJW/SdNmTtR.JiP739Whu', //csocso
          'first_name' => 'Виктор',
          'last_name' => 'Петковски',
          'phone' => '071234567',
          'address' => 'Ленинова 34/6',
          'organization_id' => '4',
          'region_id' => '1',
          'profile_image_id' => null,
          'notifications' => '1',
          'status' => 'active',
        ]);
    }
}
