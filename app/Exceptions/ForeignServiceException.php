<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ForeignServiceException extends APIException {
    protected $messageKey = 'exceptions.foreign_service';
    protected $responseCode = Response::HTTP_SERVICE_UNAVAILABLE;
}
