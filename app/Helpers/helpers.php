<?php

use App\Constants\Constants;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

if (!function_exists('sendSuccess')) {
    function sendSuccess($data = null, $message, $pagination = null, $statusCode = 200)
    {
        $responseData = [
            'status' => true,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ];
        if ($pagination) {
            $responseData['pagination'] = $pagination;
        }
        return response()->json($responseData, 200);
    }
}

if (!function_exists('sendError')) {
    function sendError($message, $data = null, $statusCode = 400)
    {
        return response()->json(
            [
                'status' => false,
                'statusCode' => $statusCode,
                'message' => $message,
                'data' => $data,
            ],
            200,
        );
    }
}

if (!function_exists('sendErrorResponse')) {
    function sendErrorResponse($message, $data = null, $statusCode = 400)
    {
        return response()->json(
            [
                'status' => false,
                'statusCode' => $statusCode,
                'message' => $message,
                'data' => $data,
            ],
            $statusCode,
        );
    }
}

if (!function_exists('storeFiles')) {
    function storeFiles($folder, $file)
    {
        return Storage::disk('public')->put($folder, $file);
    }
}

if (!function_exists('storeImagesOnSThree')) {
    function storeImagesOnSThree($image, $folder, $disk = 's3')
    {
        return Storage::disk($disk)->putFileAs($folder, $image);
    }
}

function prePageLimit()
{
    return request()->query('limit') ? request()->query('limit') : Constants::DEFAULT_PAGINATION;
}

function getLoginToken()
{
    return str_replace("Bearer ", "", request()->header('authorization'));
}

function convertToSeconds($duration)
{
    $parts = explode(':', $duration);
    return $parts[0] * 3600 + $parts[1] * 60 + $parts[2];
}

function paginate($response)
{
    return  [
        'current_page' => $response->currentPage(),
        'last_page' => $response->lastPage(),
    ];
}

function convertHoursMinstAndSecond($durationInSeconds)
{
    $hours = floor($durationInSeconds / 3600);
    $minutes = floor(($durationInSeconds % 3600) / 60);
    $seconds = $durationInSeconds % 60;
    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
}

function createDebugLogFile($path, $title = '', $data = null)
{
    return Log::build(['driver' => 'single',  'path' => storage_path('logs/' . $path . '.log'),])
        ->debug($title, array($data));
}
