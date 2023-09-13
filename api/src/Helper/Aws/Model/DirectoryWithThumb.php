<?php

declare(strict_types=1);

namespace App\Helper\Aws\Model;

class DirectoryWithThumb
{
    private string $name;

    private ?string $thumbPath;

    public function __construct(string $name, ?string $thumbPath)
    {
        $this->name = $name;
        $this->thumbPath = $thumbPath;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getThumbPath(): string
    {
        return $this->thumbPath;
    }
}
