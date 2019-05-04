<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class MissingParameterException extends APIException {
    protected $messageKey = 'exceptions.missing_parameter';
    protected $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY;
}
