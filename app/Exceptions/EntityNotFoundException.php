<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class EntityNotFoundException extends APIException {
    protected $messageKey = 'exceptions.entity_not_found';
    protected $responseCode = Response::HTTP_NOT_FOUND;
}
