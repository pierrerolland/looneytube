<?php

namespace App\Provider;

use App\Exception\CategoryNotFoundException;
use App\Exception\VideoNotFoundException;
use App\Helper\DirectoryHelper;
use App\Model\Category;

class DirectoryCategoryProvider implements CategoryProvider
{
    private string $videosDir;
    private DirectoryVideoProvider $videoProvider;
    private DirectoryHelper $directoryHelper;

    public function __construct(string $videosDir, DirectoryVideoProvider $videoProvider, DirectoryHelper $directoryHelper)
    {
        $this->videosDir = $videosDir;
        $this->videoProvider = $videoProvider;
        $this->directoryHelper = $directoryHelper;
    }

    public function getCategories(): array
    {
        $dirHelper = $this->directoryHelper;

        return array_map(function (string $dirname) use ($dirHelper) {
            $pictureFileName = sprintf('%s/%s/thumb.png', $this->videosDir, $dirname);
            return new Category($dirname, file_exists($pictureFileName) ? $dirHelper->getBrowserPath($pictureFileName) : null);
        }, $this->directoryHelper->getContent($this->videosDir, ['dir']));
    }

    public function getCategory(string $categorySlug): Category
    {
        foreach ($this->directoryHelper->getContent($this->videosDir, ['dir']) as $dirname) {
            $category = new Category($dirname);
            if ($category->slug === $categorySlug) {
                $fullDir = sprintf('%s/%s', $this->videosDir, $dirname);
                $pictureFileName = sprintf('%s/thumb.png', $fullDir);
                $category->picture = file_exists($pictureFileName) ? $this->directoryHelper->getBrowserPath($pictureFileName) : null;
                $category->videos = $this->videoProvider->getVideos($fullDir);

                return $category;
            }
        }
        throw new CategoryNotFoundException();

    }

    public function getVideoLinkFromCategory(string $categorySlug, string $videoSlug): array
    {
        $category = $this->getCategory($categorySlug);

        foreach ($category->videos as $video) {
            if ($video->slug === $videoSlug) {
                return [
                    'path' => $this->directoryHelper->getBrowserPath($video->fileName, false)
                ];
            }
        }

        throw new VideoNotFoundException();
    }
}
