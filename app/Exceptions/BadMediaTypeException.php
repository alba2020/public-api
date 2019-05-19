<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class BadMediaTypeException extends APIException {
    protected $messageKey = 'exceptions.bad_media_type';
    protected $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY;
}
