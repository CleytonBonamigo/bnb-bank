<?php

namespace Turno\Transaction\Enums;

enum TransactionType: int
{
    case DEPOSIT = 1;
    case PURCHASE = 2;
}