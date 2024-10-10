<?php

namespace App\Manager\API\Traits;

use Illuminate\Http\JsonResponse;

trait CommonResponse
{

    public int $status_code_success = 200;
    public int $status_code_failed = 460; // common failed code
    public mixed $data = null;
    public bool $status = true;
    public string $status_message = '';
    public int $status_code = 200;
    public string $status_class = 'success';

    public JsonResponse $response;


    /**
     * @return JsonResponse
     */
    final public function commonApiResponse(): JsonResponse
    {
        return response()->json([
            'status'         => $this->status,
            'status_message' => $this->status_message,
            'data'           => $this->data,
            'status_code'    => $this->status_code,
            'status_class'   => $this->status_class,
        ]);
    }
}
