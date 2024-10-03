<?php

namespace App\Enum;

enum TransactionType: string
{
    case PAYMENT = 'payment';
    case WITHDRAW = 'withdraw';
    case DEPOSIT = 'deposit';
}
