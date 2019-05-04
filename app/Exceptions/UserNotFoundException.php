<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class UserNotFoundException extends APIException {
    protected $messageKey = 'exceptions.user_not_found';
    protected $responseCode = Response::HTTP_NOT_FOUND;
}
