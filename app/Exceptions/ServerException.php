<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ServerException extends APIException {
    protected $messageKey = 'exceptions.server';
    protected $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
}
