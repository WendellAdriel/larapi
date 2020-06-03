<?php

namespace LarAPI\Core\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AccessDeniedException extends Exception implements LarAPIExceptionInterface
{
    /**
     * AccessDeniedException constructor
     */
    public function __construct()
    {
        parent::__construct('Access Denied', Response::HTTP_FORBIDDEN);
    }
}
