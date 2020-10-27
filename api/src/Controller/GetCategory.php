<?php

namespace App\Controller;

use App\Exception\CategoryNotFoundException;
use App\Exception\VideosDirectoryNotOpenableException;
use App\Provider\CategoryProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category/{categorySlug}", methods={"GET"})
 */
class GetCategory
{
    private CategoryProvider $categoryProvider;

    public function __construct(CategoryProvider $categoryProvider)
    {
        $this->categoryProvider = $categoryProvider;
    }

    public function __invoke(string $categorySlug): JsonResponse
    {
        try {
            return new JsonResponse($this->categoryProvider->getCategory($categorySlug));
        } catch (VideosDirectoryNotOpenableException | CategoryNotFoundException $e) {
            throw new NotFoundHttpException();
        }
    }
}
