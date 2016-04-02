<?php
declare(strict_types=1);
namespace Crunch\CacheControl\Console;

use Crunch\CacheControl\Console\Helper\CacheControlHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatusCommand extends AbstractHandlerCommand
{
    protected function configure ()
    {
        $this->setName('status');
        $this->setDescription('Clear Connector cache');

        parent::configure();
    }

    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $info = $this->connector->fetchStatus();

        /** @var CacheControlHelper $helper */
        $helper = $this->getHelper('cache-control');

        foreach ($info as $section => $status) {
            switch ($section) {
                case 'apcu':
                    $helper->printApcuStatus($status, $output);
                    break;
                case 'Zend OPcache':
                    $helper->printOpcacheStatus($status, $output);
                    break;
            }
        }
    }
}
