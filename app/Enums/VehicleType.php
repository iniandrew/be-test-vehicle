<?php

namespace App\Enums;

enum VehicleType: string
{
    case Car = 'car';
    case Motorcycle = 'motorcycle';

    public static function getValues(): array
    {
        return [
            self::Car->value,
            self::Motorcycle->value
        ];
    }
}
