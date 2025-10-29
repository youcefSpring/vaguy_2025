<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;


    public function getFollowersAttribute($value)
    {
        return $this->formatNumber($value);
    }

    private function formatNumber($number)
    {
        if ($number >= 1000000) {
            // Format for millions
            return number_format($number / 1000000, ($number % 1000000) ? 1 : 0, ',', '.') . 'm';
        } elseif ($number >= 1000) {
            // Format for thousands
            return number_format($number / 1000, ($number % 1000) ? 1 : 0, ',', '.') . 'k';
        } else {
            // No formatting for numbers less than 1000
            return (string) $number;
        }
    }
}
