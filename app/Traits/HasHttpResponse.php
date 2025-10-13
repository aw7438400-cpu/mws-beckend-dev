<?php

namespace App\Traits;

use App\Exceptions\HandleResourceNotExistException;
use Illuminate\Http\Resources\Json\JsonResource;

trait HasHttpResponse
{
    public function success($data = null, int $statusCode = 200, string $message = 'Successfully')
    {
        $response = [
            'status' => $statusCode,
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    public function successPaginate($data = null, int $statusCode = 200, string $message = 'Successfully')
    {
        $response = [
            'status' => $statusCode,
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            if ($data instanceof JsonResource) {
                $resourceData = $data->response()->getData(true); // ambil semua termasuk additional

                $response['data'] = [
                    'items' => $resourceData['data'] ?? [],
                    'pagination' => [
                        'current_page' => $data->currentPage(),
                        'from' => $data->firstItem(),
                        'last_page' => $data->lastPage(),
                        'path' => $this->getBasePath(),
                        'per_page' => $data->perPage(),
                        'to' => $data->lastItem(),
                        'total' => $data->total(),
                    ]
                ];

                // Tambahkan (additional) jika ada
                foreach ($resourceData as $key => $value) {
                    if (!in_array($key, ['data', 'links', 'meta'])) {
                        $response['data'][$key] = $value;
                    }
                }

                $response['links'] = [
                    'first' => $data->url(1),
                    'last' => $data->url($data->lastPage()),
                    'prev' => $data->previousPageUrl(),
                    'next' => $data->nextPageUrl(),
                ];
            } else {
                $response['data'] = $data;
            }
        }

        return response()->json($response, $statusCode);
    }


    public function getBasePath()
    {
        return url()->current();
    }

    public function error(string $message, int $statusCode = 400)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $statusCode);
    }

    public function handleResourceNotExist($model, string $message = 'Resource not found', int $statusCode = 404)
    {
        if ($model == null) {
            throw new HandleResourceNotExistException(
                $message,
                $statusCode,
            );
        }
    }

    public function handleErrorCondition($condition, $message = 'Resource not found', $statusCode = 404)
    {
        if ($condition) {
            throw new HandleResourceNotExistException(
                $message,
                $statusCode,
            );
        }
    }

    public function notFound(string $message = 'Resource not found', int $statusCode = 404)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $statusCode);
    }
}
