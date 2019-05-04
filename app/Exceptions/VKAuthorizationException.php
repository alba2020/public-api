<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class VKAuthorizationException extends APIException {
    protected $messageKey = 'exceptions.vk_authorization';
    protected $responseCode = Response::HTTP_UNAUTHORIZED;
}
