<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class CostException extends APIException {
    protected $messageKey = 'exceptions.cost';
    protected $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
}
