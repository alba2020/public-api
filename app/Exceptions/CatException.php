<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class CatException extends APIException {
    protected $messageKey = 'exceptions.cat';
    protected $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY;
}
