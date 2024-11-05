<?php

namespace App\Models;

use App\Enums\VehicleType;

/**
 * @property string $suspension_type
 * @property string $transmission_type
 */
class Motorcycle extends Vehicle
{
    protected string $collection = 'motorcycles';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = array_merge(parent::getFillable(), [
            'suspension_type',
            'transmission_type',
        ]);
    }

    protected static function booted(): void
    {
        parent::booted();
        static::creating(fn ($motorcycle) => $motorcycle->type = VehicleType::Motorcycle->value);
    }
}
