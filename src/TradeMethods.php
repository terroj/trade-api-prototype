<?php

namespace Terroj\PayeerClient;

enum TradeMethods: string
{
    case INFO = 'info';
    case ORDERS = 'orders';
    case ACCOUNT = 'account';
    case ORDER_CREATE = 'order_create';
    case ORDER_STATUS = 'order_status';
    case MY_ORDERS = 'my_orders';
}
