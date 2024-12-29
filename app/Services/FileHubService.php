<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class FileHubService
{
    public function uploadVideo($video, $path)
    {
        $originalName = time() . '-' . $video->getClientOriginalName();
        $originalName = str_replace(' ', '-', $originalName);
        $originalName = str_replace('%', '', $originalName);
        $videoPath = Storage::disk('s3')->putFileAs($path, $video, $originalName);
        $duration = FFMpeg::fromDisk('s3')
            ->open($videoPath)
            ->getDurationInSeconds();

        $timeDuration = convertHoursMinstAndSecond($duration);

        return [
            'video_path' => $videoPath,
            'duration' => $timeDuration,
        ];
    }

    public function genrateThumbnail($videoPath, $folder, $disk = "s3")
    {
        $thumbnailPath = $folder . pathinfo($videoPath, PATHINFO_FILENAME) . '.jpg';

        FFMpeg::fromDisk($disk)
            ->open($videoPath)
            ->getFrameFromSeconds(10) // Get the frame at 10 seconds (you can change this)
            ->export()
            ->toDisk($disk)
            ->save($thumbnailPath);

        return $thumbnailPath;
    }
}
