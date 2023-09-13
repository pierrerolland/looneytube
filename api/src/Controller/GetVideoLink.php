<?php

namespace App\Controller;

use App\Exception\CategoryNotFoundException;
use App\Exception\VideoNotFoundException;
use App\Exception\VideosDirectoryNotOpenableException;
use App\Provider\CategoryProvider;
use App\Provider\Resolver\CategoryProviderResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category/{categorySlug}/video/{videoSlug}", methods={"GET"})
 */
class GetVideoLink
{
    private CategoryProvider $categoryProvider;

    public function __construct(CategoryProviderResolver $categoryProviderResolver)
    {
        $this->categoryProvider = $categoryProviderResolver->resolve();
    }

    public function __invoke(string $categorySlug, string $videoSlug): JsonResponse
    {
        try {
            return new JsonResponse($this->categoryProvider->getVideoLinkFromCategory($categorySlug, $videoSlug));
        } catch (VideosDirectoryNotOpenableException | CategoryNotFoundException | VideoNotFoundException $e) {
            throw new NotFoundHttpException();
        }
    }
}
