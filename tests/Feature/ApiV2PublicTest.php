<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiV2PublicTest extends TestCase
{
    public function test_home_returns_envelope(): void
    {
        $response = $this->getJson('/api/v2/public/home');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'products',
                    'services',
                    'product_categories',
                    'service_sellers',
                ],
            ]);
    }

    public function test_marketplace_lists_items(): void
    {
        $response = $this->getJson('/api/v2/public/marketplace');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => ['items', 'slides'],
                'meta' => ['current_page', 'per_page', 'total', 'from'],
            ]);
    }

    public function test_cart_issues_token_header(): void
    {
        $response = $this->getJson('/api/v2/public/cart');

        $response->assertOk()
            ->assertHeader('X-Cart-Token')
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['token', 'count', 'total', 'lines']]);
    }
}
