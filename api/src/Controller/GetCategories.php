<?php

namespace App\Controller;

use App\Exception\VideosDirectoryNotOpenableException;
use App\Provider\CategoryProvider;
use App\Provider\Resolver\CategoryProviderResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categories", methods={"GET"})
 */
class GetCategories
{
    private CategoryProvider $categoryProvider;

    public function __construct(CategoryProviderResolver $categoryProviderResolver)
    {
        $this->categoryProvider = $categoryProviderResolver->resolve();
    }

    public function __invoke(): JsonResponse
    {
        try {
            return new JsonResponse($this->categoryProvider->getCategories());
        } catch (VideosDirectoryNotOpenableException $e) {
            throw new NotFoundHttpException();
        }
    }
}
