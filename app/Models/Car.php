<?php

namespace App\Models;

use App\Enums\VehicleType;

/**
 * @property string $passenger_capacity
 * @property string $car_type
 */
class Car extends Vehicle
{
    protected string $collection = 'vehicles';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = array_merge(parent::getFillable(), [
            'passenger_capacity',
            'car_type',
        ]);
    }

    protected static function booted(): void
    {
        parent::booted();
        static::creating(fn ($car) => $car->type = VehicleType::Car->value);
    }
}
