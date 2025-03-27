<?php

declare(strict_types=1);

namespace Compwright\ShippoPhp\Api;

use Compwright\ShippoPhp\ApiTestTrait;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    use ApiTestTrait;

    public function testListAll(): void
    {
        $expectedRequest = $this->getExpectedRequest(__DIR__ . '/Order/ListAllOrders.txt');
        $expectedResponse = $this->getExpectedResponse(200, __DIR__ . '/Order/ListAllOrders.json');
        $this->rootHandler->append($expectedResponse);
        $result = $this->api->orders->listAll([
            'page' => 2,
            'results' => 10,
            'shop_app' => 'Shippo',
        ]);
        $this->assertRequestMatchesExpected($expectedRequest, $this->rootHandler->getLastRequest());
        $this->assertSame($expectedResponse, $result->getResponse());
        $this->assertCount(1, $result);
        $this->assertEquals(
            ['#1068'],
            array_column(iterator_to_array($result), 'order_number')
        );
    }

    public function testCreate(): void
    {
        $expectedRequest = $this->getExpectedRequest(__DIR__ . '/Order/CreateOrder.txt');
        $expectedResponse = $this->getExpectedResponse(201, __DIR__ . '/Order/CreateOrder.json');
        $this->rootHandler->append($expectedResponse);
        $result = $this->api->orders->create([
            'currency' => 'USD',
            'notes' => 'This customer is a VIP',
            'order_number' => '#1068',
            'order_status' => 'PAID',
            'placed_at' => '2016-09-23T01:28:12Z',
            'shipping_cost' => '12.83',
            'shipping_cost_currency' => 'USD',
            'shipping_method' => 'USPS First Class Package',
            'subtotal_price' => '12.1',
            'total_price' => '24.93',
            'total_tax' => '0.0',
            'weight' => '0.4',
            'weight_unit' => 'lb',
            'from_address' => [
                'name' => 'Shwan Ippotle',
                'company' => 'Shippo',
                'street1' => '215 Clayton St.',
                'street2' => 'string',
                'street3' => '',
                'street_no' => '',
                'city' => 'San Francisco',
                'state' => 'CA',
                'zip' => '94117',
                'country' => 'US',
                'phone' => '+1 555 341 9393',
                'email' => 'shippotle@shippo.com',
                'is_residential' => true,
                'metadata' => 'Customer ID 123456',
                'validate' => true,
            ],
            'to_address' => [
                'name' => 'Shwan Ippotle',
                'company' => 'Shippo',
                'street1' => '215 Clayton St.',
                'street2' => 'string',
                'street3' => '',
                'street_no' => '',
                'city' => 'San Francisco',
                'state' => 'CA',
                'zip' => '94117',
                'country' => 'US',
                'phone' => '+1 555 341 9393',
                'email' => 'shippotle@shippo.com',
                'is_residential' => true,
                'metadata' => 'Customer ID 123456',
                'validate' => true,
            ],
            'line_items' => [[
                'currency' => 'USD',
                'manufacture_country' => 'US',
                'max_delivery_time' => '2016-07-23T00:00:00Z',
                'max_ship_time' => '2016-07-23T00:00:00Z',
                'quantity' => 20,
                'sku' => 'HM-123',
                'title' => 'Hippo Magazines',
                'total_price' => '12.1',
                'variant_title' => 'June Edition',
                'weight' => '0.4',
                'weight_unit' => 'lb',
            ]],
        ]);
        $this->assertRequestMatchesExpected($expectedRequest, $this->rootHandler->getLastRequest());
        $this->assertSame($expectedResponse, $result->getResponse());
        $this->assertEquals(
            'adcfdddf8ec64b84ad22772bce3ea37a',
            $result->data()['object_id'] ?? null
        );
    }

    public function testGetById(): void
    {
        $expectedRequest = $this->getExpectedRequest(__DIR__ . '/Order/GetOrder.txt');
        $expectedResponse = $this->getExpectedResponse(200, __DIR__ . '/Order/GetOrder.json');
        $this->rootHandler->append($expectedResponse);
        $result = $this->api->orders->getById('4f2bc588e4e5446cb3f9fdb7cd5e190b');
        $this->assertRequestMatchesExpected($expectedRequest, $this->rootHandler->getLastRequest());
        $this->assertSame($expectedResponse, $result->getResponse());
        $this->assertEquals(
            '#1068',
            $result->data()['order_number'] ?? null
        );
    }
}
