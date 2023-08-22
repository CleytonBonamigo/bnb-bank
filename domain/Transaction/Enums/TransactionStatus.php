<?php

namespace Turno\Transaction\Enums;

enum TransactionStatus: int
{
    case PENDING = 1;
    case APPROVED = 2;
    case REJECTED = 3;
}