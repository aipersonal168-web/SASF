<?php

namespace App\Helpers;

class NumberHelper
{
    public static function toKhmer($number)
    {
        $khmer = ['០','១','២','៣','៤','៥','៦','៧','៨','៩'];
        return str_replace(range(0,9), $khmer, (string) $number);
    }
}