<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SaleRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'quantity' => 'required|int|gt:0',
            'unit_cost' => 'required|numeric|gt:0|decimal:0,2',
            'selling_price' => 'required|numeric|gt:0|decimal:0,2',
            'product_id' => 'required|int|gt:0',
        ];
    }

    public function withValidator(Validator $validator)
    {
        if ($validator->fails()) {
            $validator->errors()->add('SaleRecordRequest', 'validation failed');
            return;
        }

        $validator->after(function (Validator $validator) {
            try {
                $quantity = $this->get('quantity');
                $unitCost = $this->get('unit_cost');
                $sellingPrice = $this->get('selling_price');
                $productId = $this->get('product_id');

                if (!Product::where('id', $productId)->exists()) {
                    $validator->errors()->add('product_id', 'invalid product id');
                }

                if (!Sale::validateSellingPrice($quantity, $unitCost, $sellingPrice)) {
                    $validator->errors()->add('selling_price', 'invalid selling price');
                }

            } catch (\Exception $e) {
                $validator->errors()->add('SaleRecordRequest', 'validation failed');
            }
        });
    }

    /**
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        Log::debug('SaleRecordRequest, failed validation', ['failed' => $validator->failed()]);
        throw new HttpResponseException(response()->json($validator->errors(), Response::HTTP_BAD_REQUEST));
    }
}
