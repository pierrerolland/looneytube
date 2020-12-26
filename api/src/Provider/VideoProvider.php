<?php

namespace App\Provider;

use App\Exception\VideosDirectoryNotOpenableException;
use App\Helper\DirectoryHelper;
use App\Model\Video;

class VideoProvider
{
    private DirectoryHelper $directoryHelper;

    public function __construct(DirectoryHelper $directoryHelper)
    {
        $this->directoryHelper = $directoryHelper;
    }

    public function getVideos(string $rootDir): array
    {
        if (!is_dir($rootDir)) {
            throw new VideosDirectoryNotOpenableException();
        }

        $thumbsDir = sprintf('%s/thumbs', $rootDir);
        if (!is_dir($thumbsDir)) {
            mkdir($thumbsDir);
        }

        return array_map(
            fn (string $fileName) => $this->generateVideo($rootDir, $fileName),
            $this->directoryHelper->getContent($rootDir, ['file'], ['mkv', 'mp4', 'avi'])
        );
    }

    private function generateVideo(string $rootDir, string $fileName): Video
    {
        $videoPath = sprintf('%s/%s', $rootDir, $fileName);
        $pathInfo = pathinfo($videoPath);
        $thumbPath = sprintf('%s/thumbs/%s.jpg', $rootDir, $pathInfo['filename']);

        return new Video(
            $this->directoryHelper->getBrowserPath($videoPath),
            $pathInfo['filename'],
            $this->directoryHelper->getBrowserPath($thumbPath)
        );
    }
}
