<?php

declare(strict_types=1);

namespace Compwright\ShippoPhp;

use Compwright\EasyApi\ApiClient;

/**
 * @see https://docs.goshippo.com/shippoapi/public-api/#tag/Overview
 */
class Api
{
    public readonly Api\Order $orders;
    public readonly Api\Shipment $shipments;
    public readonly Api\Transaction $transactions;

    public function __construct(ApiClient $client)
    {
        $this->orders = new Api\Order($client);
        $this->shipments = new Api\Shipment($client);
        $this->transactions = new Api\Transaction($client);
    }
}
