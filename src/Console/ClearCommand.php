<?php
declare(strict_types=1);
namespace Crunch\CacheControl\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCommand extends AbstractHandlerCommand
{
    protected function configure ()
    {
        $this->setName('clear');
        $this->setDescription('Clear Connector cache');

        parent::configure();
    }

    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $info = $this->connector->clearCache();
        foreach ($info as $section => $success) {
            if ($success) {
                $output->writeln("<info>$section OK</info>");
            } else {
                $output->writeln("<error>$section NOT OK</error>");
            }
        }
    }
}
