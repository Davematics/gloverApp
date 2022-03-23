<?php


namespace App\Traits;
use Illuminate\Http\Response;

trait RespondsWithHttpStatus
{
    protected function response($message, $data = [], $status=true, $statusCode = Response::HTTP_OK)
    {
        return response([
            'status' => $status,
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

}
