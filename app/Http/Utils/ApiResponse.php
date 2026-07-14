<?php

namespace App\Http\Utils;

/*
 * API Response
 */

class ApiResponse
{
    const HTTP_OK = 200;

    const HTTP_CREATED = 201;

    const HTTP_BAD_REQUEST = 400;

    const HTTP_UNAUTHORIZED = 401;

    const HTTP_FORBIDDEN = 403;

    const HTTP_NOT_FOUND = 404;

    const HTTP_INTERNAL_SERVER_ERROR = 500;

    const HTTP_SERVICE_UNAVAILABLE = 503;

    const HTTP_UNPROCESSABLE_ENTITY = 422;

    const HTTP_TOO_MANY_REQUESTS = 429;
    /*
     * Response Success Status
     * @param String $message
     * @param Array $data
     * @param Array $pagination
     *
     * @return Array
     */

    public static function success($message = '', $data = [], $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $code);
    }
    /*
     * Response Fail Status
     *
     * @param String $code
     * @param Array|String $errors
     * @param Array $data
     *
     * @return Array
     */

    public static function error($errors = [], $code = 500, $message = null)
    {
        $response = [
            'success' => false,
        ];

        if (is_string($errors) || ! is_null($message)) {
            $response['message'] = $message ?? $errors;
        }
        $response['errors'] = $errors;

        $statusCode = is_numeric($code) ? (int) $code : 500;

        return response()->json($response, $statusCode);
    }
}
