<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Unpaid = 'unpaid';
    case Paid = 'paid';
    case Cancelled = 'cancelled';
    case Shiped = 'shiped';
    case Completed = 'completed';

    public static function getStatuses()
    {
        return [
            self::Paid, 
            self::Unpaid, 
            self::Cancelled, 
            self::Shiped,
            self::Completed,
        ];
    }
}