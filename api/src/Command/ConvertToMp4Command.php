<?php

namespace App\Command;

use App\Helper\DirectoryHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ConvertToMp4Command extends Command
{
    protected static $defaultName = 'app:convert';

    protected function configure()
    {
        $this
            ->setDescription("Convert MKV to MP4")
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
        foreach ($directoryHelper->getContent($dir, ['file'], ['mkv', 'avi', 'mp4']) as $videoName) {
            $mkvPath = sprintf('%s%s', $dir, $videoName);
            $pathInfo = pathinfo(sprintf('%s%s', $dir, $videoName));

            if ($pathInfo['extension'] === 'mp4') {
                rename($mkvPath, $mkvPath . '-2');
                $mkvPath = $mkvPath . '-2';
            }

            $mp4Path = sprintf('%s%s.mp4', $dir, $pathInfo['filename']);
            shell_exec(sprintf('ffmpeg -i "%s" "%s"', $mkvPath, $mp4Path));
        }

        return Command::SUCCESS;
    }
}
