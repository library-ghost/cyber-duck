<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    use HasFactory;
    const SHIPPING = 10.00;
    const PROFIT_MARGIN = 0.25;

    protected $casts = [
        'created_at' => 'datetime',
    ];
    protected $fillable = [
        'quantity',
        'unit_cost',
        'selling_price',
        'product_id',
    ];

    public static function createWithAttributes(array $attributes): self
    {
        $attributes['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
        $attributes['unit_cost'] = self::convertToCents($attributes['unit_cost']);
        $attributes['selling_price'] = self::convertToCents($attributes['selling_price']);

        return self::create($attributes);
    }

    public static function convertToCents(float $amount): int
    {
        return bcmul($amount, 100);
    }

    public static function getSalesRecords(): array
    {
        return DB::table('products')
            ->join('sales', 'products.id', '=', 'sales.product_id')
            ->select('sales.quantity', 'sales.quantity', 'sales.unit_cost', 'sales.selling_price', 'sales.created_at', 'products.name as product')
            ->get()
            ->toArray();
    }

    public function asTableData(): array
    {
        $data = $this->toArray();
        $product = Product::where('id', '=', $data['product_id'])->first();
        $createdAt = Carbon::parse($data['created_at']);
        return [
            'product' => $product->name,
            'quantity' => $data['quantity'],
            'unit_cost' => $data['unit_cost'],
            'selling_price' => $data['selling_price'],
            'created_at' => $createdAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function validateSellingPrice(int $quantity, float $unit_cost, float $selling_price): bool
    {
        return self::calculateSellingPrice($quantity, $unit_cost) == $selling_price;
    }

    public static function calculateSellingPrice(int $quantity, float $unit_cost): float
    {
        $cost = $quantity * $unit_cost;
        $sp = ($cost / ( 1 - self::PROFIT_MARGIN ) ) + self::SHIPPING;
        return floor($sp * 100) / 100;
    }
}
