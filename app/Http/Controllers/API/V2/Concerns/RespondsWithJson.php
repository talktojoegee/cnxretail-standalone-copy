<?php

namespace App\Http\Controllers\API\V2\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

trait RespondsWithJson
{
    protected function ok(mixed $data = null, string $message = 'OK', int $status = 200, array $meta = []): JsonResponse
    {
        return $this->respond(true, $message, $data, $meta, null, $status);
    }

    protected function created(mixed $data = null, string $message = 'Created'): JsonResponse
    {
        return $this->ok($data, $message, 201);
    }

    protected function fail(string $message, int $status = 400, mixed $errors = null, mixed $data = null): JsonResponse
    {
        return $this->respond(false, $message, $data, [], $errors, $status);
    }

    protected function paginated(AbstractPaginator $paginator, mixed $resource = null, string $message = 'OK'): JsonResponse
    {
        $items = $resource ?? $paginator->items();

        return $this->ok($items, $message, 200, [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ]);
    }

    protected function respond(
        bool $success,
        string $message,
        mixed $data = null,
        array $meta = [],
        mixed $errors = null,
        int $status = 200
    ): JsonResponse {
        if ($data instanceof JsonResource || $data instanceof ResourceCollection) {
            $data = $data->resolve();
        }

        $payload = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];

        if ($meta !== []) {
            $payload['meta'] = $meta;
        }

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }
}
