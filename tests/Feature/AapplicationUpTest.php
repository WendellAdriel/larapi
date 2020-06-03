<?php

namespace Tests\Feature;

use Tests\TestCase;

class AapplicationUpTest extends TestCase
{
    public function test_application_is_up()
    {
        $this->json('GET', '/')
            ->assertStatus(200)
            ->assertJsonStructure(['application', 'environment','status']);
    }
}
