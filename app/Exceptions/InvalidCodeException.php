<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class InvalidCodeException extends APIException {
    protected $messageKey = 'exceptions.invalid_code';
    protected $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY;
}
