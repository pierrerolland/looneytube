<?php

declare(strict_types=1);

namespace App\Controller;

use App\Helper\Aws\AwsHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/aws-media/{path}", methods={"GET"}, name="aws_media")
 */
class AwsMedia
{
    private AwsHelper $awsHelper;

    public function __construct(AwsHelper $awsHelper)
    {
        $this->awsHelper = $awsHelper;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        return new RedirectResponse($this->awsHelper->getPreSignedUrl(urldecode($request->get('path'))));
    }
}
