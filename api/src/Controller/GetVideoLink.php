<?php

namespace App\Controller;

use App\Exception\CategoryNotFoundException;
use App\Exception\VideoNotFoundException;
use App\Exception\VideosDirectoryNotOpenableException;
use App\Provider\CategoryProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category/{categorySlug}/video/{videoSlug}", methods={"GET"})
 */
class GetVideoLink
{
    private CategoryProvider $categoryProvider;

    public function __construct(CategoryProvider $categoryProvider)
    {
        $this->categoryProvider = $categoryProvider;
    }

    public function __invoke(string $categorySlug, string $videoSlug)
    {
        try {
            return new JsonResponse($this->categoryProvider->getVideoLinkFromCategory($categorySlug, $videoSlug));
        } catch (VideosDirectoryNotOpenableException | CategoryNotFoundException | VideoNotFoundException $e) {
            throw new NotFoundHttpException();
        }
    }
}
