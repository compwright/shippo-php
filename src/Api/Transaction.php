<?php

declare(strict_types=1);

namespace Compwright\ShippoPhp\Api;

use Compwright\EasyApi\ApiClient;
use Compwright\EasyApi\Operation;
use Compwright\EasyApi\Result\Json\Result;

/**
 * @see https://docs.goshippo.com/shippoapi/public-api/#tag/Transactions
 */
class Transaction
{
    public function __construct(private ApiClient $client)
    {
    }

    /**
     * @param array<string, mixed> $body
     *
     * @see https://docs.goshippo.com/shippoapi/public-api/#operation/CreateTransaction
     */
    public function create(array $body): Result
    {
        $op = Operation::fromSpec('POST /transactions')
            ->setBody($body);
        return $this->client->__invoke($op, new Result());
    }
}
