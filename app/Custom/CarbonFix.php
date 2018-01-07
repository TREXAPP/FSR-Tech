<?php
namespace FSR\Custom;

use Carbon\Carbon as Carbon2;
use Illuminate\Support\Carbon;

/**
 * A bundle of my own custom functions that I think will be useful to be written as methods,
 * since probably I'll be needing them in the future. Or maybe not, who knows :)
 */
class CarbonFix extends Carbon
{
    public function diffForHumans(Carbon2 $other = null, $absolute = false, $short = false)
    {

  //  $returnString = parent::diffForHumans($other, $absolute, $short);

        if ($this->getLocale() == 'mk') {
            if ($this->diffInHours(null, false) == 11) {
                return "Пред 11 часа";
            }
            if ($this->diffInHours(null, false) == -11) {
                return "11 часа од сега";
            }
            if ($this->diffInMinutes(null, false) == 11) {
                return "Пред 11 минути";
            }
            if ($this->diffInMinutes(null, false) == -11) {
                return "11 минути од сега";
            }
            if ($this->diffInMonths(null, false) == 11) {
                return "Пред 11 месеци";
            }
            if ($this->diffInMonths(null, false) == -11) {
                return "11 месеци од сега";
            }
            if ($this->diffInYears(null, false) == 11) {
                return "Пред 11 години";
            }
            if ($this->diffInYears(null, false) == -11) {
                return "11 години од сега";
            }
        }
        return parent::diffForHumans($other, $absolute, $short);
    }
}
