<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class NotEnoughMediaException extends APIException {
    protected $messageKey = 'exceptions.not_enough_media';
    protected $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY;
}
