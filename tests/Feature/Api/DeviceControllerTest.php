<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeviceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_device()
    {
        $response = $this->postJson('/api/devices/register', [
            'device_uuid' => '550e8400-e29b-41d4-a716-446655440000',
            'push_token' => 'test-token-123',
        ]);

        $response->assertOk()
            ->assertJson(['status' => 'registered']);
    }

    public function test_deregister_device()
    {
        $this->postJson('/api/devices/register', [
            'device_uuid' => '550e8400-e29b-41d4-a716-446655440000',
            'push_token' => 'test-token-123',
        ]);

        $response = $this->postJson('/api/devices/deregister', [
            'device_uuid' => '550e8400-e29b-41d4-a716-446655440000',
        ]);

        $response->assertOk()
            ->assertJson(['status' => 'deregistered']);
    }
}