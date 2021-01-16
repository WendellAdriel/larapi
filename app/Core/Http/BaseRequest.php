<?php

namespace LarAPI\Core\Http;

use Illuminate\Foundation\Http\FormRequest;
use LarAPI\Modules\Common\Support\DTOs\DTOInterface;

abstract class BaseRequest extends FormRequest
{
    /**
     * @return DTOInterface
     */
    abstract public function getDTO(): DTOInterface;
}
