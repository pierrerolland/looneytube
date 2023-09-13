<?php

declare(strict_types=1);

namespace App\Provider\Resolver;

use App\Helper\Aws\AwsHelper;
use App\Provider\AwsCategoryProvider;
use App\Provider\CategoryProvider;
use App\Provider\DirectoryCategoryProvider;

class CategoryProviderResolver
{
    private DirectoryCategoryProvider $directoryCategoryProvider;

    private AwsCategoryProvider $awsCategoryProvider;

    private AwsHelper $awsHelper;

    public function __construct(
        DirectoryCategoryProvider $directoryCategoryProvider,
        AwsCategoryProvider $awsCategoryProvider,
        AwsHelper $awsHelper
    ) {
        $this->directoryCategoryProvider = $directoryCategoryProvider;
        $this->awsCategoryProvider = $awsCategoryProvider;
        $this->awsHelper = $awsHelper;
    }

    public function resolve(): CategoryProvider
    {
        if ($this->awsHelper->isActivated()) {
            return $this->awsCategoryProvider;
        }

        return $this->directoryCategoryProvider;
    }
}
