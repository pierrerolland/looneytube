<?php

namespace App\Model;

use Cocur\Slugify\Slugify;

class Video
{
    public string $name;
    public string $fileName;
    public string $slug;
    public ?string $picture;

    public function __construct(string $fileName, string $name, string $picture = null)
    {
        $this->name = $name;
        $this->fileName = $fileName;
        $this->slug = Slugify::create()->slugify($name);
        $this->picture = $picture;
    }
}
