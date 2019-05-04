<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class EmailAuthorizationException extends APIException {
    protected $messageKey = 'exceptions.email_authorization';
    protected $responseCode = Response::HTTP_UNAUTHORIZED;
}
