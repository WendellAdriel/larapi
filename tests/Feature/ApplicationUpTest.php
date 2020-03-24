<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApplicationUpTest extends TestCase
{
    /**
     * Checks if the application is up
     *
     * @return void
     */
    public function testApplicationUp()
    {
        $response = $this->json('GET', '/');
        $response->assertStatus(200)
            ->assertJson([
                'application' => true,
                'environment' => true,
                'status'      => true
            ]);
    }
}
