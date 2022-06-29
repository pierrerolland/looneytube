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

        usort($out, function (string $name1, string $name2) {
            $name1 = trim(substr($name1, 0, strrpos($name1, '.')));
            $name2 = trim(substr($name2, 0, strrpos($name2, '.')));

            if ($name1 === $name2)  {
                return 0;
            }

            return $name1 < $name2 ? -1 : 1;
        });

        return $out;
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
