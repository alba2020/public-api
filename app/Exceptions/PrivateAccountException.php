<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class PrivateAccountException extends APIException {
    protected $messageKey = 'exceptions.private_account';
    protected $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY;
}
