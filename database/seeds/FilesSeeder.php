<?php
use FSR\File;
use Illuminate\Database\Seeder;

class FilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        File::create([
          'id' => '1000',
          'path_to_file' => '/upload',
          'filename' => 'food-general.jpg',
          'original_name' => 'food-general.jpg',
          'extension' => 'jpg',
          'purpose' => 'food types default image',
          'description' => 'food types default image from seed',
        ]);

        File::create([
          'id' => '1001',
          'path_to_file' => '/upload',
          'filename' => 'fruits.jpg',
          'original_name' => 'fruits.jpg',
          'extension' => 'jpg',
          'purpose' => 'food types default image',
          'description' => 'food types default image from seed',
        ]);

        File::create([
          'id' => '1002',
          'path_to_file' => '/upload',
          'filename' => 'vegetables.jpg',
          'original_name' => 'vegetables.jpg',
          'extension' => 'jpg',
          'purpose' => 'food types default image',
          'description' => 'food types default image from seed',
        ]);

        File::create([
          'id' => '1003',
          'path_to_file' => '/upload',
          'filename' => 'dairy.jpg',
          'original_name' => 'dairy.jpg',
          'extension' => 'jpg',
          'purpose' => 'food types default image',
          'description' => 'food types default image from seed',
        ]);

        File::create([
          'id' => '1004',
          'path_to_file' => '/upload',
          'filename' => 'conditory.jpg',
          'original_name' => 'conditory.jpg',
          'extension' => 'jpg',
          'purpose' => 'food types default image',
          'description' => 'food types default image from seed',
        ]);

        File::create([
          'id' => '1005',
          'path_to_file' => '/upload',
          'filename' => 'meats.jpg',
          'original_name' => 'meats.jpg',
          'extension' => 'jpg',
          'purpose' => 'food types default image',
          'description' => 'food types default image from seed',
        ]);
    }
}
