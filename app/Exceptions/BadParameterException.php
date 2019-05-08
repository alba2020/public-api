<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class BadParameterException extends APIException {
    protected $messageKey = 'exceptions.bad_parameter';
    protected $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY;
}
