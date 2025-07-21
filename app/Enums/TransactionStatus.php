<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case Pending = 'pending';
    case Settlement = 'settlement';
    case Deny = 'deny';
    case Expire = 'expire';
    case Cancel = 'cancel';
    case Refund = 'refund';
    case Chargeback = "chargeback";
}
