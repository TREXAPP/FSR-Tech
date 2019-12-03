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
        Validator::extend('custom_greater_than_date', function ($attribute, $value, $parameters, $validator) {
            $now = CarbonFix::now();
            $data = $validator->getData();
            $date_to_compare_to = CarbonFix::parse($data[$parameters[0]]);
            $time_type = $data[$parameters[1]];

            switch ($time_type) {
              case 'hours':
                  $date_to_compare = $now->addHours($value);
                break;
              case 'days':
                  $date_to_compare = $now->addDays($value);
                break;
              case 'weeks':
                  $date_to_compare = $now->addWeeks($value);
                break;
            }

            if ($date_to_compare) {
                return $date_to_compare_to <= $date_to_compare;
            }
            return false;
        });

        Validator::replacer('custom_greater_than_date', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':field', $parameters[0], $message);
        });

        Validator::extend('custom_between_dates', function ($attribute, $value, $parameters, $validator) {
            $now = CarbonFix::now();
            $data = $validator->getData();
            $date_begin = CarbonFix::parse($data[$parameters[0]]);
            $date_end_value =  $data[$parameters[1]];
            $time_type_end = $data[$parameters[2]];
            $time_type_expires = $data[$parameters[3]];

            switch ($time_type_end) {
              case 'hours':
                  $date_end = $now->addHours($date_end_value);
                break;
              case 'days':
                  $date_end = $now->addDays($date_end_value);
                break;
              case 'weeks':
                  $date_end = $now->addWeeks($date_end_value);
                break;
            }

            switch ($time_type_expires) {
              case 'hours':
                  $date_expires = $now->addHours($value);
                break;
              case 'days':
                  $date_expires = $now->addDays($value);
                break;
              case 'weeks':
                  $date_expires = $now->addWeeks($value);
                break;
            }

            if ($date_begin && $date_end && $date_expires) {
                if (($date_expires <= $date_end) && ($date_expires >= $date_begin)) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        });

        Validator::replacer('custom_between_dates', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':field', $parameters[0], $message);
        });

        
        Validator::extend('custom_before_date_and_now', function ($attribute, $value, $parameters, $validator) {
            $now = CarbonFix::now();
            $data = $validator->getData();
            $date_begin = $now;
            $date_to_check_value =  $data[$attribute];
            $time_type = $data[$parameters[0]];
            $date_end = CarbonFix::parse($data[$parameters[1]]);

            switch ($time_type) {
              case 'hours':
                  $date_to_check = $now->addHours($date_to_check_value);
                break;
              case 'days':
                  $date_to_check = $now->addDays($date_to_check_value);
                break;
              case 'weeks':
                  $date_to_check = $now->addWeeks($date_to_check_value);
                break;
            }

            if ($date_begin && $date_end && $date_to_check) {
                if (($date_to_check <= $date_end) && ($date_to_check >= $date_begin)) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        });

        Validator::replacer('custom_before_date_and_now', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':field', $attribute, $message);
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
