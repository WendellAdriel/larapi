<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;

class AapplicationUpTest extends TestCase
{
    public function test_application_is_up()
    {
        $this->json('GET', '/')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['application', 'environment','status']);
    }
}
