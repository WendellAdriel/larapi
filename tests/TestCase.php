<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Assert as PHPUnit;
use Illuminate\Testing\TestResponse;
use LarAPI\Repositories\Auth\UserRepository;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function actingAsUser(int $userId)
    {
        $userRepository  = $this->app->make(UserRepository::class);

        $user  = $userRepository->getByOrFail('id', $userId);

        $token = Auth::login($user);
        $this->withHeaders(['Authorization' => 'Bearer ' . $token]);
        return $this;
    }

    /**
     * @param mixed $response
     * @param array $dataStructure
     */
    protected function assertPaginatedDataResponse(TestResponse $response, array $dataStructure): void
    {
        $response->assertJsonStructure(
            [
                'data'       => [$dataStructure],
                'pagination' => ['page_count', 'total', 'total_all']
            ],
            $this->decodeResponseJson($response)
        );
    }

    /**
     * @param mixed $response
     * @param int   $status
     * @return void
     */
    protected function assertSimpleSuccessResponse($response, int $status = Response::HTTP_CREATED): void
    {
        $response->assertStatus($status);
        $content = \json_decode($response->getContent(), true);
        $this->assertTrue($content['success']);
    }

    /**
     * Validate and return the decoded response JSON.
     *
     * @param TestResponse $response
     * @param string|null  $key
     * @return mixed
     */
    protected function decodeResponseJson(TestResponse $response, $key = null)
    {
        $decodedResponse = $response->baseResponse instanceof StreamedResponse
            ? json_decode($response->streamedContent(), true)
            : json_decode($response->getContent(), true);

        if ($decodedResponse === false || is_null($decodedResponse)) {
            if ($this->exception) {
                throw $this->exception;
            } else {
                PHPUnit::fail('Invalid JSON was returned from the route.');
            }
        }

        return data_get($decodedResponse, $key);
    }
}
