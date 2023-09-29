<?php

declare(strict_types=1);

namespace App\Helper\Aws;

use App\Helper\Aws\Model\DirectoryWithThumb;
use App\Helper\Aws\Model\VideoWithThumb;
use AsyncAws\S3\Input\GetObjectRequest;
use AsyncAws\S3\S3Client;
use AsyncAws\S3\ValueObject\AwsObject;
use AsyncAws\S3\ValueObject\CommonPrefix;
use Symfony\Component\Routing\RouterInterface;

class AwsHelper
{
    private const ALLOWED_VIDEO_TYPES = ['mp4', 'mkv'];

    private const PUBLIC_ACCESS_DELAY = '+360 min';

    private ?S3Client $s3Client = null;

    private string $awsS3BucketName;

    private RouterInterface $router;

    public function __construct(
        RouterInterface $router,
        string $awsAccessKeyId,
        string $awsSecretAccessKey,
        string $awsRegion,
        string $awsS3BucketName
    ) {
        $this->router = $router;

        if ($awsAccessKeyId) {
            $this->s3Client = new S3Client([
                'accessKeyId' => $awsAccessKeyId,
                'accessKeySecret' => $awsSecretAccessKey,
                'region' => $awsRegion,
            ]);
        }

        $this->awsS3BucketName = $awsS3BucketName;
    }

    public function isActivated(): bool
    {
        return !!$this->s3Client;
    }

    /**
     * @return DirectoryWithThumb[]
     */
    public function listRootDirectories(): array
    {
        $response = $this->s3Client->listObjectsV2([
            'Delimiter' => '/',
            'Bucket' => $this->awsS3BucketName
        ])->getCommonPrefixes();

        return array_map(
            fn (CommonPrefix $commonPrefix) => new DirectoryWithThumb(
                substr($commonPrefix->getPrefix(), 0, -1),
                $this->getMediaUrl(sprintf('%sthumb.png', $commonPrefix->getPrefix()))
            ),
            iterator_to_array($response)
        );
    }

    /**
     * @return VideoWithThumb[]
     */
    public function listDirectoryVideos(string $directory): array
    {
        $response = array_map(
            static fn (AwsObject $object) => substr($object->getKey(), strpos($object->getKey(), '/') + 1),
            iterator_to_array($this->s3Client->listObjectsV2([
                'Prefix' => $directory . '/',
                'Bucket' => $this->awsS3BucketName
            ])->getIterator())
        );

        $videosKeys = array_filter($response, [$this, 'isKeyAllowed']);

        return array_map(
            fn (string $key) => new VideoWithThumb(
                substr($key, 0, -4),
                $this->getMediaUrl(sprintf('%s/%s', $directory, $key)),
                in_array(sprintf('thumbs/%s.jpg', substr($key, 0, -4)), $response)
                    ? $this->getMediaUrl(sprintf('%s/thumbs/%s.jpg', $directory, substr($key, 0, -4)))
                    : null
            ),
            array_values($videosKeys)
        );
    }

    public function getPreSignedUrl(string $path): string
    {
        $input = new GetObjectRequest([
            'Bucket' => $this->awsS3BucketName,
            'Key' => $path,
        ]);

        return $this->s3Client->presign($input, new \DateTimeImmutable(self::PUBLIC_ACCESS_DELAY));
    }

    private function getMediaUrl(string $path): string
    {
        return $this->router->generate(
            'aws_media',
            ['path' => urlencode($path)],
            RouterInterface::ABSOLUTE_URL
        );
    }

    private function isKeyAllowed(string $key): bool
    {
        return in_array(
            array_reverse(explode('.', $key))[0],
            self::ALLOWED_VIDEO_TYPES
        );
    }
}
