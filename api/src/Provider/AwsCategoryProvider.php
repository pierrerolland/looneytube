<?php

declare(strict_types=1);

namespace App\Provider;

use App\Exception\CategoryNotFoundException;
use App\Exception\VideoNotFoundException;
use App\Helper\Aws\AwsHelper;
use App\Helper\Aws\Model\DirectoryWithThumb;
use App\Helper\Aws\Model\VideoWithThumb;
use App\Model\Category;
use App\Model\Video;

class AwsCategoryProvider implements CategoryProvider
{
    private AwsHelper $awsHelper;

    public function __construct(AwsHelper $awsHelper)
    {
        $this->awsHelper = $awsHelper;
    }

    public function getCategories(): array
    {
        return array_map(
            static fn (DirectoryWithThumb $dir) => new Category($dir->getName(), $dir->getThumbPath()),
            $this->awsHelper->listRootDirectories()
        );
    }

    public function getCategory(string $categorySlug): Category
    {
        /** @var Category[] $categories */
        $categories = array_values(
            array_filter(
                $this->getCategories(),
                static fn (Category $category) => $category->slug === $categorySlug
            )
        );

        if (count($categories) ===  0) {
            throw new CategoryNotFoundException();
        }

        $category = new Category($categories[0]->name, $categories[0]->picture);

        $category->videos = array_map(
            static fn (VideoWithThumb $video) => new Video(
                $video->getUrl(),
                $video->getName(),
                $video->getThumbPath()
            ),
            $this->awsHelper->listDirectoryVideos($categories[0]->name)
        );

        return $category;
    }

    public function getVideoLinkFromCategory(string $categorySlug, string $videoSlug): array
    {
        $category = $this->getCategory($categorySlug);

        foreach ($category->videos as $video) {
            if ($video->slug === $videoSlug) {
                return [
                    'path' => $video->fileName
                ];
            }
        }

        throw new VideoNotFoundException();
    }
}
