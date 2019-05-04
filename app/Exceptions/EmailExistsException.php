<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class EmailExistsException extends APIException {
    protected $messageKey = 'exceptions.email_exists';
    protected $responseCode = Response::HTTP_CONFLICT;
}
