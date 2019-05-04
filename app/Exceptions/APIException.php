<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

abstract class APIException extends Exception {

    protected $messageKey = "exceptions.default";
    protected $responseData = [];
    protected $responseCode = Response::HTTP_OK;

    /** @noinspection PhpHierarchyChecksInspection */
    protected function __construct(string $message = "", int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public static function create($args = []) {
        $e = new static;

        foreach($args as $k => $v) {
            $e->responseData[$k] = $v;
        }

        return $e;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request) {
        $this->responseData['error'] = get_class($this);
        $this->responseData['message'] = __($this->messageKey);
//        $this->responseData['locale'] = App::getLocale();
//        $this->responseData['translated'] = __($this->msg);

        return response()->json($this->responseData, $this->responseCode);
    }
}
