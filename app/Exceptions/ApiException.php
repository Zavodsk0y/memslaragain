<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiException extends HttpResponseException
{
    public function __construct(string $message = "Нарушение правил валидации", int $code = 422, $errors = [])
    {
        $data = [
            'success' => false,
            'code' => $code,
            'message' => $message
        ];

        if (count($errors) > 0) $data['error']['errors'] = $errors;

        parent::__construct(response()->json($data)->setStatusCode($code));
    }
}
