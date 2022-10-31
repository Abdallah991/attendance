<?php

namespace App\Traits;


// Creating custome responses
trait HttpResponses
{

    // success response
    protected function success($data, $message = null, $code = 200)
    {

        return response()->json([
            'status' => 'Request was successful!',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    // error response
    protected function error($data, $message = null, $code)
    {

        return response()->json([
            'status' => 'Error had occured ...',
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
