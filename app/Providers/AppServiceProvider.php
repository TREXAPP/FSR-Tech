<?php

namespace FSR\Providers;

use FSR\Custom\CarbonFix;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // use schema to set default length - fix
        Schema::defaultStringLength(191);
        Carbon::setLocale(config('app.locale'));

        //custom validator
        Validator::extend('greater_than_date', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            $sell_by_date = CarbonFix::parse($data[$parameters[0]]);
            $time_type = $data[$parameters[1]];

            switch ($time_type) {
              case 'hours':
                  $donation_expires_date = CarbonFix::now()->addHours($value);
                break;
              case 'days':
                  $donation_expires_date = CarbonFix::now()->addDays($value);
                break;
              case 'weeks':
                  $donation_expires_date = CarbonFix::now()->addWeeks($value);
                break;
            }

            if ($donation_expires_date) {
                return $sell_by_date > $donation_expires_date;
            }
            return false;
        });

        Validator::replacer('greater_than_date', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':field', $parameters[0], $message);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
