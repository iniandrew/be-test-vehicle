<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

/**
 * @property int    $year_of_manufacture
 * @property string $color
 * @property float  $price
 * @property int    $stock
 * @property string $type
 * @property string $engine
 */
class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'year_of_manufacture',
        'engine',
        'color',
        'price',
        'stock',
        'type'
    ];
}
