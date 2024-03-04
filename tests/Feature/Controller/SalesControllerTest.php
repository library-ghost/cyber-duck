<?php

namespace Tests\Feature\Controller;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Traits\SalesTrait;

class SalesControllerTest extends TestCase
{
    use SalesTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_access_sales_if_authenticated()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $url = '/sales';

        $response = $this->get($url);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_cannot_access_sales_if_not_authenticated()
    {
        $url = '/sales';

        $response = $this->get($url);
        $response->assertRedirect();
    }


    public function test_create_sales_record_with_valid_data()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $url = '/sales/record';

        $payload = $this->getValidSaleData();
        $headers = [
            'X-CSRF-TOKEN' => csrf_token(),
            'Content-Type' => 'application/json'
        ];

        // Create sale via endpoint
        $response = $this->json('POST', $url, $payload, $headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['sale']);
    }

    public function test_cannot_create_sales_record_with_invalid_selling_price()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $url = '/sales/record';

        $payload = $this->getInvalidPriceSaleData();
        $headers = [
            'X-CSRF-TOKEN' => csrf_token(),
            'Content-Type' => 'application/json'
        ];

        $response = $this->json('POST', $url, $payload, $headers);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);

    }

    public function test_cannot_create_sales_record_with_invalid_field_types()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $url = '/sales/record';

        $payload = $this->getInvalidTypeSaleData();
        $headers = [
            'X-CSRF-TOKEN' => csrf_token(),
            'Content-Type' => 'application/json'
        ];

        $response = $this->json('POST', $url, $payload, $headers);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);

    }

    public function test_cannot_create_sales_record_with_invalid_float_types()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $url = '/sales/record';

        $payload = $this->getInvalidFloatSaleData();
        $headers = [
            'X-CSRF-TOKEN' => csrf_token(),
            'Content-Type' => 'application/json'
        ];

        $response = $this->json('POST', $url, $payload, $headers);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);

    }

    public function test_cannot_create_sales_record_with_invalid_product_id()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $url = '/sales/record';

        $payload = $this->getInvalidProductSaleData();
        $headers = [
            'X-CSRF-TOKEN' => csrf_token(),
            'Content-Type' => 'application/json'
        ];

        $response = $this->json('POST', $url, $payload, $headers);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);

    }

    public function test_cannot_create_sales_record_if_not_authenticated()
    {
        $url = '/sales/record';

        $payload = $this->getInvalidTypeSaleData();
        $headers = [
            'X-CSRF-TOKEN' => csrf_token(),
            'Content-Type' => 'application/json'
        ];

        $response = $this->json('POST', $url, $payload, $headers);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_can_calculate_selling_price_with_valid_data()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $url = '/sales/calculate-price';

        $payload = $this->getValidCalcData();
        $headers = [
            'X-CSRF-TOKEN' => csrf_token(),
            'Content-Type' => 'application/json'
        ];

        $response = $this->json('POST', $url, $payload, $headers);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'selling_price' => 60.16,
        ]);
    }

    public function test_cannot_calculate_selling_price_with_invalid_data()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $url = '/sales/calculate-price';

        $payload = $this->getInvalidCalcData();
        $headers = [
            'X-CSRF-TOKEN' => csrf_token(),
            'Content-Type' => 'application/json'
        ];

        $response = $this->json('POST', $url, $payload, $headers);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_cannot_calculate_selling_price_if_not_authenticated()
    {
        $url = '/sales/calculate-price';

        $payload = $this->getInvalidCalcData();
        $headers = [
            'X-CSRF-TOKEN' => csrf_token(),
            'Content-Type' => 'application/json'
        ];

        $response = $this->json('POST', $url, $payload, $headers);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

}
