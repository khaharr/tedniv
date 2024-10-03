<?php
// src/Enum/DepositType.php
namespace App\Enum;

enum DepositType: string
{
    case PAYMENT = 'payment';
    case WITHDRAW = 'withdraw';
    case DEPOSIT = 'deposit';
}
