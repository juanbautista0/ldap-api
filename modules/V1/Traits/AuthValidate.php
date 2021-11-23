<?php

namespace Modules\V1\Traits;

use React\Http\Message\Response;
use \Exception;

trait AuthValidate
{
    public function inputValidate(array | object $inputs, array $required = [], string $contentType = "application/json; charset=utf-8"): void
    {
        foreach ($required as $key) (isset($inputs[$key])) ? true : throw new Exception("400 Bad Request, {$key} is a required field", 1);
    }
}
