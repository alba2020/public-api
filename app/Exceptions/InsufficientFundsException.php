<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class InsufficientFundsException extends APIException {
    protected $messageKey = 'exceptions.insufficient_funds';
    protected $responseCode = Response::HTTP_BAD_REQUEST;
}
