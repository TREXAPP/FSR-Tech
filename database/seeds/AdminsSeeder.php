<?php
use FSR\Admin;
use Illuminate\Database\Seeder;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
          'id' => '1',
          'email' => 'admin@admin.mk',
          'password' => '$2y$10$x8XYHVwUBZSxtEBjtgNFj..gwHj84TAWKzSKx1WwMhKDdV972Hqte', //fsradmin$
          'first_name' => 'Admin',
          'last_name' => 'Admin',
          'profile_image_id' => null,
        ]);
    }
}
