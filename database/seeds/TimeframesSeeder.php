<?php
use FSR\Timeframe;
use FSR\TransportType;
use Illuminate\Database\Seeder;

class TimeframesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Timeframe::create([
          'id' => '1',
          'day' => 'Понеделник',
          'hours_from' => '00:00',
          'hours_to' => '06:00',
        ]);
        Timeframe::create([
          'id' => '2',
          'day' => 'Понеделник',
          'hours_from' => '06:00',
          'hours_to' => '12:00',
        ]);
        Timeframe::create([
          'id' => '3',
          'day' => 'Понеделник',
          'hours_from' => '12:00',
          'hours_to' => '18:00',
        ]);
        Timeframe::create([
          'id' => '4',
          'day' => 'Понеделник',
          'hours_from' => '18:00',
          'hours_to' => '00:00',
        ]);
        Timeframe::create([
          'id' => '5',
          'day' => 'Вторник',
          'hours_from' => '00:00',
          'hours_to' => '06:00',
        ]);
        Timeframe::create([
          'id' => '6',
          'day' => 'Вторник',
          'hours_from' => '06:00',
          'hours_to' => '12:00',
        ]);
        Timeframe::create([
          'id' => '7',
          'day' => 'Вторник',
          'hours_from' => '12:00',
          'hours_to' => '18:00',
        ]);
        Timeframe::create([
          'id' => '8',
          'day' => 'Вторник',
          'hours_from' => '18:00',
          'hours_to' => '00:00',
        ]);
        Timeframe::create([
          'id' => '9',
          'day' => 'Среда',
          'hours_from' => '00:00',
          'hours_to' => '06:00',
        ]);
        Timeframe::create([
          'id' => '10',
          'day' => 'Среда',
          'hours_from' => '06:00',
          'hours_to' => '12:00',
        ]);
        Timeframe::create([
          'id' => '11',
          'day' => 'Среда',
          'hours_from' => '12:00',
          'hours_to' => '18:00',
        ]);
        Timeframe::create([
          'id' => '12',
          'day' => 'Среда',
          'hours_from' => '18:00',
          'hours_to' => '00:00',
        ]);
        Timeframe::create([
          'id' => '13',
          'day' => 'Четврток',
          'hours_from' => '00:00',
          'hours_to' => '06:00',
        ]);
        Timeframe::create([
          'id' => '14',
          'day' => 'Четврток',
          'hours_from' => '06:00',
          'hours_to' => '12:00',
        ]);
        Timeframe::create([
          'id' => '15',
          'day' => 'Четврток',
          'hours_from' => '12:00',
          'hours_to' => '18:00',
        ]);
        Timeframe::create([
          'id' => '16',
          'day' => 'Четврток',
          'hours_from' => '18:00',
          'hours_to' => '00:00',
        ]);
        Timeframe::create([
          'id' => '17',
          'day' => 'Петок',
          'hours_from' => '00:00',
          'hours_to' => '06:00',
        ]);
        Timeframe::create([
          'id' => '18',
          'day' => 'Петок',
          'hours_from' => '06:00',
          'hours_to' => '12:00',
        ]);
        Timeframe::create([
          'id' => '19',
          'day' => 'Петок',
          'hours_from' => '12:00',
          'hours_to' => '18:00',
        ]);
        Timeframe::create([
          'id' => '20',
          'day' => 'Петок',
          'hours_from' => '18:00',
          'hours_to' => '00:00',
        ]);
        Timeframe::create([
          'id' => '21',
          'day' => 'Сабота',
          'hours_from' => '00:00',
          'hours_to' => '06:00',
        ]);
        Timeframe::create([
          'id' => '22',
          'day' => 'Сабота',
          'hours_from' => '06:00',
          'hours_to' => '12:00',
        ]);
        Timeframe::create([
          'id' => '23',
          'day' => 'Сабота',
          'hours_from' => '12:00',
          'hours_to' => '18:00',
        ]);
        Timeframe::create([
          'id' => '24',
          'day' => 'Сабота',
          'hours_from' => '18:00',
          'hours_to' => '00:00',
        ]);
        Timeframe::create([
          'id' => '25',
          'day' => 'Недела',
          'hours_from' => '00:00',
          'hours_to' => '06:00',
        ]);
        Timeframe::create([
          'id' => '26',
          'day' => 'Недела',
          'hours_from' => '06:00',
          'hours_to' => '12:00',
        ]);
        Timeframe::create([
          'id' => '27',
          'day' => 'Недела',
          'hours_from' => '12:00',
          'hours_to' => '18:00',
        ]);
        Timeframe::create([
          'id' => '28',
          'day' => 'Недела',
          'hours_from' => '18:00',
          'hours_to' => '00:00',
        ]);
    }
}
