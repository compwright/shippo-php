<?php

declare(strict_types=1);

namespace Compwright\ShippoPhp;

use Compwright\EasyApi\ApiClient;
use Compwright\EasyApi\OperationRequestFactory;
use Compwright\EasyApi\Serializer\SerializerCollection;
use Compwright\ShouldRetry\RetryAfter;
use Compwright\ShouldRetry\ShouldRetry;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\HttpFactory;
use InvalidArgumentException;

class ApiFactory
{
    /** @var ?callable */
    private $rootHandler;

    public function __construct(?callable $rootHandler = null)
    {
        $this->rootHandler = $rootHandler;
    }

    private function operationRequestFactory(): OperationRequestFactory
    {
        $httpFactory = new HttpFactory();
        $serializers = SerializerCollection::default()
            ->setDefaultSerializer('application/json');
        return new OperationRequestFactory($httpFactory, $httpFactory, $serializers);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @see https://docs.goshippo.com/docs/guides_general/authentication/#use-your-secure-key
     * @see https://docs.goshippo.com/docs/api_concepts/apiversioning
     * @see https://docs.goshippo.com/docs/api_concepts/ratelimits/
     */
    public function new(string $token, ?string $version = null): Api
    {
        if (!$token) {
            throw new InvalidArgumentException('$token is required');
        }

        $handler = HandlerStack::create($this->rootHandler);
        $handler->push(
            Middleware::retry(
                new ShouldRetry(),
                new RetryAfter()
            ),
            'retry'
        );

        $headers = [
            'Authorization' => 'ShippoToken ' . $token,
        ];

        if ($version) {
            $headers['Shippo-API-Version'] = $version;
        }

        return new Api(
            new ApiClient(
                new Client([
                    'base_uri' => 'https://api.goshippo.com',
                    'headers' => $headers,
                    'handler' => $handler,
                ]),
                $this->operationRequestFactory()
            )
        );
    }
}
