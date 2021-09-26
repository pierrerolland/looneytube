<?php

namespace App\Command;

use App\Helper\DirectoryHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CreateThumbsCommand extends Command
{
    protected static $defaultName = 'app:create-thumbs';

    protected function configure()
    {
        $this
            ->setDescription("Create thumbs for a given dir")
            ->addArgument('dir', InputArgument::REQUIRED, 'MKV dir')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = $input->getArgument('dir');
        if (substr($dir, -1) !== '/') {
            $dir = $dir . '/';
        }

        $directoryHelper = new DirectoryHelper(realpath(__DIR__ . '/../../public'), new RequestStack());
        foreach ($directoryHelper->getContent($dir, ['file'], ['avi', 'mkv', 'mp4']) as $videoName) {
            $pathInfo = pathinfo(sprintf('%s%s', $dir, $videoName));
            $thumbPath = sprintf('%sthumbs/%s.jpg', $dir, $pathInfo['filename']);
            
            if (file_exists($thumbPath)) {
                continue;
            }

            try {
                $video = new \PHPVideoToolkit\Video(sprintf('%s%s', $dir, $videoName));
                $video->extractFrame(new \PHPVideoToolkit\Timecode(120))->save($thumbPath);
            } catch (\Exception $exception) {
                $output->writeln('Could not create thumb, copying default thumb instead');
                copy(sprintf('%sthumb.png', $dir), $thumbPath);
            }
        }

        return Command::SUCCESS;
    }
}
