<?php

declare(strict_types=1);

namespace App\Provider;

use App\Exception\CategoryNotFoundException;
use App\Exception\VideoNotFoundException;
use App\Model\Category;

interface CategoryProvider
{
    public function getCategories(): array;

    /**
     * @throws CategoryNotFoundException
     */
    public function getCategory(string $categorySlug): Category;

    /**
     * @throws CategoryNotFoundException
     * @throws VideoNotFoundException
     */
    public function getVideoLinkFromCategory(string $categorySlug, string $videoSlug): array;
}
