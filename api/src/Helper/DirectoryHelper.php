<?php

namespace App\Helper;

use App\Exception\VideosDirectoryNotOpenableException;
use Symfony\Component\HttpFoundation\RequestStack;

class DirectoryHelper
{
    private string $publicDir;
    private RequestStack $requestStack;

    public function __construct(string $publicDir, RequestStack $requestStack)
    {
        $this->publicDir = $publicDir;
        $this->requestStack = $requestStack;
    }

    public function getContent(string $directory, array $allowedTypes = ['file', 'dir'], $allowedExtensions = []): array
    {
        $dirHandle = @opendir($directory);

        if (!$dirHandle) {
            throw new VideosDirectoryNotOpenableException();
        }

        $out = [];
        while ($entry = readdir($dirHandle)) {
            $fullName = sprintf('%s/%s', $directory, $entry);
            $pathInfo = pathinfo($fullName);
            if (
                substr($entry, 0, 1) === '.' ||
                !in_array(filetype($fullName), $allowedTypes) ||
                (count($allowedExtensions) > 0 && !in_array($pathInfo['extension'], $allowedExtensions))
            ) {
                continue 1;
            }

            $out[] = $entry;
        }

        closedir($dirHandle);

        usort(
            $out,
            fn (string $name1, string $name2) => strcasecmp($this->transformBeforeSort($name1), $this->transformBeforeSort($name2))
        );

        return $out;
    }

    private function transformBeforeSort(string $name): string
    {
        return preg_replace('/([^.]+)(\.[^.]+)?$/', '$1', trim($name)) . ' 01';
    }

    public function getBrowserPath(string $fullPath = null, $urlEncode = true): ?string
    {
        if (null === $fullPath) {
            return null;
        }

        $pathInfo = pathinfo($fullPath);

        $webPath = preg_replace(
            sprintf('#^%s(.+)$#', preg_quote($this->publicDir)),
            sprintf('%s$1', $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost()),
            $pathInfo['dirname']
        );

        return sprintf(
            '%s/%s.%s',
            $webPath,
            $urlEncode ? rawurlencode($pathInfo['filename']) : $pathInfo['filename'],
            $pathInfo['extension']
        );
    }
}
