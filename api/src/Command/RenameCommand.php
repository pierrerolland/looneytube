<?php

namespace App\Command;

use App\Helper\DirectoryHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class RenameCommand extends Command
{
    protected static $defaultName = 'app:rename';

    protected function configure()
    {
        $this
            ->setDescription("Rename a set of files")
            ->addArgument('dir', InputArgument::REQUIRED, 'Videos dir')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = $input->getArgument('dir');
        if (substr($dir, -1) !== '/') {
            $dir = $dir . '/';
        }

        $directoryHelper = new DirectoryHelper(realpath(__DIR__ . '/../../public'), new RequestStack());
        foreach ($directoryHelper->getContent($dir, ['file'], ['mkv', 'avi']) as $videoName) {
            $newName = $this->applyRule($videoName);

            rename($dir . $videoName, $dir . $newName);
            $output->writeln(sprintf('<fg=green>Renamed "%s" to "%s"</>', $videoName, $newName));
        }

        return Command::SUCCESS;
    }

    protected function applyRule(string $videoName)
    {
        return preg_replace('#^N° \d+ - \d+ Walt Disney - (.+)$#', '$1', $videoName);
    }
}
