<?php
namespace Crunch\CacheControl\Console;

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

        foreach ($info as $section => $status) {
            switch ($section) {
                case 'apc':
                case 'apcu':
                    $this->printApcStatus($status, $output);
                    break;
                case 'Zend OPcache':
                    $this->printOpcacheStatus($status, $output);
                    break;
            }
        }
    }

    private function printApcStatus (array $status, OutputInterface $output)
    {
        /** @var FormatterHelper $formatter */
        $formatter = $this->getHelperSet()->get('formatter');
        $output->writeln('<info>APC/APCu SMA status</info>');
        $formattedLine = $formatter->formatBlock(
            array(
                'Number segments     ' . sprintf('%12u', $status['sma']['num_seg']),
                'Segment size        ' . sprintf('%12.2f', $status['sma']['seg_size'] / 1024 / 1024) .  ' MiB',
                'Available Memory    ' . sprintf('%12.2f', $status['sma']['avail_mem'] / 1024 / 1024) . ' MiB',
            ),
            'comment'
        );
        $output->writeln($formattedLine);

        $output->writeln('<info>APC/APCu user cache status</info>');
        $formattedLine = $formatter->formatBlock(
            array(
                'Slots               ' . sprintf('%12u', $status['cache']['nslots']),
                'Time To Live        ' . sprintf('%12u', $status['cache']['ttl']),
                'Hits                ' . sprintf('%12u', $status['cache']['nhits']),
                'Misses              ' . sprintf('%12u', $status['cache']['nmisses']),
                'Inserts             ' . sprintf('%12u', $status['cache']['ninserts']),
                'Entries             ' . sprintf('%12u', $status['cache']['nentries']),
                'Expunges            ' . sprintf('%12u', $status['cache']['nexpunges']),
                'Memory Size         ' . sprintf('%12.2f', $status['cache']['mem_size'] / 1024 / 1024) . ' Mib',
            ),
            'comment'
        );
        $output->writeln($formattedLine);

        if (!isset($status['system'])) {
            return;
        }

        $output->writeln('<info>APC/APCu system cache status</info>');
        $formattedLine = $formatter->formatBlock(
            array(
                'Slots               ' . sprintf('%12u', $status['system']['nslots']),
                'Time To Live        ' . sprintf('%12u', $status['system']['ttl']),
                'Hits                ' . sprintf('%12u', $status['system']['nhits']),
                'Misses              ' . sprintf('%12u', $status['system']['nmisses']),
                'Inserts             ' . sprintf('%12u', $status['system']['ninserts']),
                'Entries             ' . sprintf('%12u', $status['system']['nentries']),
                'Expunges            ' . sprintf('%12u', $status['system']['nexpunges']),
                'Memory Size         ' . sprintf('%12.2f', $status['system']['mem_size'] / 1024 / 1024) . ' Mib',
            ),
            'comment'
        );
        $output->writeln($formattedLine);
    }

    private function printOpcacheStatus ($status, OutputInterface $output)
    {
        /** @var FormatterHelper $formatter */
        $formatter = $this->getHelperSet()->get('formatter');
        $output->writeln('<info>Opcache memory status</info>');
        $formattedLine = $formatter->formatBlock(
            array(
                'Used Memory         ' . sprintf('%12.2f', $status['memory_usage']['used_memory'] / 1024 / 1024) . ' MiB',
                'Free Memory         ' . sprintf('%12.2f', $status['memory_usage']['free_memory'] / 1024 / 1024) . ' MiB',
                'Wasted Memory       ' . sprintf('%12.2f', $status['memory_usage']['wasted_memory'] / 1024 / 1024) . ' MiB',
                'Wasted Memory rate  ' . sprintf('%12.2f', $status['memory_usage']['current_wasted_percentage']) . ' %',
            ),
            'comment'
        );
        $output->writeln($formattedLine);

        $output->writeln('<info>Opcache cache status</info>');
        $formattedLine = $formatter->formatBlock(
            array(
                'Cached scripts      ' . sprintf('%12u', $status['opcache_statistics']['num_cached_scripts']),
                'Cached keys         ' . sprintf('%12u', $status['opcache_statistics']['num_cached_keys']),
                'Max. Cache keys     ' . sprintf('%12u', $status['opcache_statistics']['max_cached_keys']),
                'Hits                ' . sprintf('%12u', $status['opcache_statistics']['hits']),
                'Misses              ' . sprintf('%12u', $status['opcache_statistics']['misses']),
                'Hit rate            ' . sprintf('%12.2f', $status['opcache_statistics']['opcache_hit_rate']) . ' %',
            ),
            'comment'
        );
        $output->writeln($formattedLine);
    }
}
