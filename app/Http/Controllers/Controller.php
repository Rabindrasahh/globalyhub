<?php

namespace App\Http\Controllers;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

abstract class Controller
{
    public function error($message = "Error", $code = Response::HTTP_BAD_REQUEST, $errors = [])
    {
        $errorMessage = [
            'url' => request()->path(),
            'status' => 'error',
            'code' => $code,
            'message' => $message,
            'errors' => $errors
        ];

        return response()->json($errorMessage, $code);
    }


    public function success($data, $message = "Success", $code = Response::HTTP_OK)
    {
        $response = [
            'status' => 'success',
            'code' => $code,
            'message' => $message,
        ];

        if ($data instanceof ResourceCollection) {
            $response['data'] = isset($data->response()->getData(true)['data']) ? $data->response()->getData(true)['data'] : false;
            $response['links'] = isset($data->response()->getData(true)['links']) ? $data->response()->getData(true)['links'] : false;
            $response['meta'] = isset($data->response()->getData(true)['meta']) ? $data->response()->getData(true)['meta'] : false;
        } else {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}
