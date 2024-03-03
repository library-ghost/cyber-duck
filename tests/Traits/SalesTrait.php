<?php

namespace Tests\Traits;

use App\Models\Sale;

trait SalesTrait
{
    use SetupDatabaseMigrations;

    protected function createSale(array $data = []): ?Sale
    {
        return Sale::createWithAttributes([
            'quantity' => $data['quantity'] ?? 5,
            'unit_cost' => $data['unit_cost'] ?? 25.99,
            'selling_price' => $data['selling_price'] ?? Sale::calculateSellingPrice(5, 25.99),
        ]);
    }

    protected function getValidSaleData(): array
    {
        return [
            'quantity' => 3,
            'unit_cost' => 12.54,
            'selling_price' => 60.16,
        ];
    }

    protected function getInvalidPriceSaleData(): array
    {
        return [
            'quantity' => 3,
            'unit_cost' => 12.54,
            'selling_price' => 23.42,
        ];
    }

    protected function getInvalidTypeSaleData(): array
    {
        return [
            'quantity' => 'three',
            'unit_cost' => 'forty five',
            'selling_price' => 'total',
        ];
    }

    protected function getInvalidFloatSaleData(): array
    {
        return [
            'quantity' => 3,
            'unit_cost' => 12.5411111,
            'selling_price' => 23.42,
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
