<?php

namespace App\Model;

use Cocur\Slugify\Slugify;

class Category implements \JsonSerializable
{
    public string $name;
    public string $slug;
    public ?string $picture;

    /** @var Video[]|null  */
    public ?array $videos;

    public function __construct(string $name, string $picture = null)
    {
        $this->name = $name;
        $this->slug = Slugify::create()->slugify($name);
        $this->picture = $picture;
        $this->videos = null;
    }

    public function jsonSerialize(): array
    {
        $out = [
            'name' => $this->name,
            'slug' => $this->slug,
            'picture' => $this->picture
        ];

        if (is_array($this->videos)) {
            $out['videos'] = $this->videos;
        }

        return $out;
    }
}
