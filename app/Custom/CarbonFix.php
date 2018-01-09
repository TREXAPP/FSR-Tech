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
                return "пред 11 часа";
            }
            if ($this->diffInHours(null, false) == -11) {
                return "11 часа од сега";
            }
            if ($this->diffInHours(null, false) == 21) {
                return "пред 21 час";
            }
            if ($this->diffInHours(null, false) == -21) {
                return "21 час од сега";
            }
            if ($this->diffInMinutes(null, false) == 11) {
                return "пред 11 минути";
            }
            if ($this->diffInMinutes(null, false) == -11) {
                return "11 минути од сега";
            }
            if ($this->diffInMinutes(null, false) == 21) {
                return "пред 21 минута";
            }
            if ($this->diffInMinutes(null, false) == -21) {
                return "21 минута од сега";
            }
            if ($this->diffInMinutes(null, false) == 31) {
                return "пред 31 минута";
            }
            if ($this->diffInMinutes(null, false) == -31) {
                return "31 минута од сега";
            }
            if ($this->diffInMinutes(null, false) == 41) {
                return "пред 41 минута";
            }
            if ($this->diffInMinutes(null, false) == -41) {
                return "41 минута од сега";
            }
            if ($this->diffInMinutes(null, false) == 51) {
                return "пред 51 минута";
            }
            if ($this->diffInMinutes(null, false) == -51) {
                return "51 минута од сега";
            }
            if ($this->diffInMonths(null, false) == 11) {
                return "пред 11 месеци";
            }
            if ($this->diffInMonths(null, false) == -11) {
                return "11 месеци од сега";
            }
            if ($this->diffInYears(null, false) == 11) {
                return "пред 11 години";
            }
            if ($this->diffInYears(null, false) == -11) {
                return "11 години од сега";
            }
            if ($this->diffInYears(null, false) == 21) {
                return "пред 21 година";
            }
            if ($this->diffInYears(null, false) == -21) {
                return "21 година од сега";
            }
            if ($this->diffInYears(null, false) == 31) {
                return "пред 31 година";
            }
            if ($this->diffInYears(null, false) == -31) {
                return "31 година од сега";
            }
            if ($this->diffInYears(null, false) == 41) {
                return "пред 41 година";
            }
            if ($this->diffInYears(null, false) == -41) {
                return "41 година од сега";
            }
            if ($this->diffInYears(null, false) == 51) {
                return "пред 51 година";
            }
            if ($this->diffInYears(null, false) == -51) {
                return "51 година од сега";
            }
        }
        return parent::diffForHumans($other, $absolute, $short);
    }
}
