<?php

declare(strict_types=1);

namespace Compwright\ShippoPhp\Api;

use Compwright\EasyApi\ApiClient;
use Compwright\EasyApi\Operation;
use Compwright\EasyApi\Result\Json\IterableResult;
use Compwright\EasyApi\Result\Json\Result;

/**
 * @see https://docs.goshippo.com/shippoapi/public-api/#tag/Orders
 */
class Order
{
    public function __construct(private ApiClient $client)
    {
    }

    /**
     * @param array<string, mixed> $queryParams
     * 
     * @see https://docs.goshippo.com/shippoapi/public-api/#operation/ListOrders
     */
    public function listAll(array $queryParams = []): IterableResult
    {
        $op = Operation::fromSpec('GET /orders');
        if ($queryParams) {
            $op->setQueryParams($queryParams);
        }
        return $this->client->__invoke($op, new IterableResult('results'));
    }

    /**
     * @param array<string, mixed> $body
     *
     * @see https://docs.goshippo.com/shippoapi/public-api/#operation/CreateOrder
     */
    public function create(array $body): Result
    {
        $op = Operation::fromSpec('POST /orders')
            ->setBody($body);
        return $this->client->__invoke($op, new Result());
    }

    /**
     * @see https://docs.goshippo.com/shippoapi/public-api/#operation/GetOrder
     */
    public function getById(string $orderId): Result
    {
        $op = Operation::fromSpec('GET /orders/%s')
            ->bindArgs($orderId);
        return $this->client->__invoke($op, new Result());
    }
}
