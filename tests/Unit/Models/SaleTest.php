<?php

namespace Tests\Unit\Models;

use App\Models\Sale;
use Tests\TestCase;
use Tests\Traits\SalesTrait;

class SaleTest extends TestCase
{
    use SalesTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_create_sale_record()
    {
        $data = $this->getValidSaleData();
        Sale::createWithAttributes($data);
        $sale = Sale::latest()->first();
        $this->assertNotEmpty($sale);
        $dbSellingPrice = Sale::convertToCents($data['selling_price']);
        $dbUnitCost = Sale::convertToCents($data['unit_cost']);
        $this->assertEquals($data['quantity'], $sale->quantity);
        $this->assertEquals($dbSellingPrice, $sale->selling_price);
        $this->assertEquals($dbUnitCost, $sale->unit_cost);
    }

    public function test_get_sale_as_table_data()
    {
        $data = $this->getValidSaleData();
        $sale = Sale::createWithAttributes($data);
        $expectedArray = [
            'quantity' => $data['quantity'],
            'unit_cost' => Sale::convertToCents($data['unit_cost']),
            'selling_price' => Sale::convertToCents($data['selling_price']),
        ];
        $this->assertEquals($expectedArray, $sale->asTableData());

    }

    public function test_can_calculate_selling_price()
    {
        $data = $this->getValidCalcData();
        $price = Sale::calculateSellingPrice($data['quantity'], $data['unit_cost']);
        $this->assertEquals(60.16, $price);
    }

}
