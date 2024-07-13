<?php

namespace App\Enums;

enum Status: string
{
    case READY = 'Ready';
    case PENDING = 'Pending';
    case DELIVERED = 'Delivered';
    case INPREPARATION = 'In Preparation';
}
