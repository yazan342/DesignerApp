<?php


function apiResponse($message, $data = "", $status = "success")
{
    return response()->json([
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ]);
}


function apiErrors($message, $status = "error")
{
    return response()->json([
        'message' => $message,
        'status' => $status,
    ])->setStatusCode(400);
}



function uploadImage($image)
{
    return $image->store('designs', 'public');
}
