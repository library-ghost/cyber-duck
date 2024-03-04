<?php

namespace Tests\Traits;

trait SalesTrait
{
    use SetupDatabaseMigrations;

    protected function getValidSaleData(): array
    {
        return [
            'product_id' => 1,
            'quantity' => 3,
            'unit_cost' => 12.54,
            'selling_price' => 60.16,
        ];
    }

    protected function getInvalidPriceSaleData(): array
    {
        return [
            'product_id' => 1,
            'quantity' => 3,
            'unit_cost' => 12.54,
            'selling_price' => 23.42,
        ];
    }

    protected function getInvalidTypeSaleData(): array
    {
        return [
            'product_id' => 1,
            'quantity' => 'three',
            'unit_cost' => 'forty five',
            'selling_price' => 'total',
        ];
    }

    protected function getInvalidFloatSaleData(): array
    {
        return [
            'product_id' => 1,
            'quantity' => 3,
            'unit_cost' => 12.5411111,
            'selling_price' => 23.42,
        ];
    }


    protected function getInvalidProductSaleData(): array
    {
        return [
            'product_id' => 3,
            'quantity' => 3,
            'unit_cost' => 12.54,
            'selling_price' => 60.16,
        ];
    }

    protected function getValidCalcData(): array
    {
        return [
            'quantity' => 3,
            'unit_cost' => 12.54,
        ];
    }

    protected function getInvalidCalcData(): array
    {
        return [
            'quantity' => 'three',
            'unit_cost' => 'forty five',
        ];
    }
}
