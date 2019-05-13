<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class FBAuthorizationException extends APIException {
    protected $messageKey = 'exceptions.fb_authorization';
    protected $responseCode = Response::HTTP_UNAUTHORIZED;
}
