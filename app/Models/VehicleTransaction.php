<?php

namespace App\Models;


use MongoDB\Laravel\Eloquent\Model;

/**
 * @property string $vehicle_id
 * @property int    $quantity
 * @property string $transaction_date
 */
class VehicleTransaction extends Model
{
    protected $connection = 'mongodb';
    protected string $collection = 'vehicle_transactions';

    protected $fillable = [
        'vehicle_id',
        'quantity',
        'transaction_date'
    ];
}
