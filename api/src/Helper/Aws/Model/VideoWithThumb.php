<?php

declare(strict_types=1);

namespace App\Helper\Aws\Model;

class VideoWithThumb
{
    private string $name;

    private string $url;

    private ?string $thumbPath;

    public function __construct(string $name, string $url, ?string $thumbPath)
    {
        $this->name = $name;
        $this->url = $url;
        $this->thumbPath = $thumbPath;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getThumbPath(): string
    {
        return $this->thumbPath;
    }
}
