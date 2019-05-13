<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ValidationException extends APIException {
    protected $messageKey = 'exceptions.validation';
    protected $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY;
}
