<?php

namespace App\Api\Controllers;

use Illuminate\Http\JsonResponse;

abstract class BaseController
{
    /**
     * Danh sách HTTP status code hợp lệ.
     */
    protected array $validStatusCodes = [
        // 1xx Informational
        100,
        101,
        102,
        103,

        // 2xx Success
        200,
        201,
        202,
        203,
        204,
        205,
        206,

        // 3xx Redirection
        300,
        301,
        302,
        303,
        304,
        307,
        308,

        // 4xx Client Errors
        400,
        401,
        402,
        403,
        404,
        405,
        406,
        408,
        409,
        410,
        411,
        412,
        413,
        414,
        415,
        422,
        429,

        // 5xx Server Errors
        500,
        501,
        502,
        503,
        504,
        505,
    ];

    /**
     * Response chung.
     *
     * @param bool $status           true/false
     * @param string $message        Nội dung message
     * @param mixed $data            Dữ liệu trả về
     * @param int $statusCode        HTTP status code
     * @param string $version        Mặc định v1
     */
    protected function responseCommon(
        bool $status,
        string $message,
        $data = null,
        int $statusCode = 200,
        string $version = 'v1'
    ): JsonResponse {
        // Kiểm tra status code hợp lệ
        if (!$this->isValidStatusCode($statusCode)) {
            return response()->json([
                'version' => $version,
                'status'  => false,
                'message' => "Trạng thái HTTP không hợp lệ: {$statusCode}",
                'data'    => null,
                'code'    => 400,
            ], 400);
        }

        return response()->json([
            'version' => $version,
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }

    /**
     * Kiểm tra status code hợp lệ.
     */
    private function isValidStatusCode(int $code): bool
    {
        return in_array($code, $this->validStatusCodes);
    }
}
