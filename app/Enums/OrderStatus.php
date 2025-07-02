<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Done = 'done';
    case cancelled = 'cancelled';
}
