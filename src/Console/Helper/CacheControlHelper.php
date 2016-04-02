<?php
namespace Crunch\CacheControl\Console\Helper;

use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheControlHelper extends Helper
{

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     *
     * @api
     */
    public function getName()
    {
        return 'cache-control';
    }

    public function createConnectorInstace (InputInterface $input)
    {

    }

    public function printOpcacheStatus($status, OutputInterface $output)
    {
        /** @var FormatterHelper $formatter */
        $formatter = $this->getHelperSet()->get('formatter');
        $output->writeln('<info>Opcache memory status</info>');
        $formattedLine = $formatter->formatBlock(
            [
                sprintf('%-30s %9.2f MiB/%.2f MiB', 'Used/Free Memory', $status['memory_usage']['used_memory'] / 1024 / 1024, $status['memory_usage']['free_memory'] / 1024 / 1024),
                sprintf('%-30s %9.2f MiB (%.2f%%)', 'Wasted Memory (rate)', $status['memory_usage']['wasted_memory'] / 1024 / 1024, $status['memory_usage']['current_wasted_percentage']),
            ],
            'comment'
        );
        $output->writeln($formattedLine);

        $output->writeln('<info>Opcache cache status</info>');
        $formattedLine = $formatter->formatBlock(
            [
                sprintf('%-30s %9u/%u (%u)', 'Cached scripts/keys (Max keys)', $status['opcache_statistics']['num_cached_scripts'], $status['opcache_statistics']['num_cached_keys'], $status['opcache_statistics']['max_cached_keys']),
                sprintf('%-30s %9u/%u (%.2f%%)', 'Hits/Misses (rate)', $status['opcache_statistics']['hits'], $status['opcache_statistics']['misses'], $status['opcache_statistics']['opcache_hit_rate']),
            ],
            'comment'
        );
        $output->writeln($formattedLine);
    }

    public function printApcStatus(array $status, OutputInterface $output)
    {
        $this->printApcuStatus($status, $output);
    }

    public function printApcuStatus(array $status, OutputInterface $output)
    {
        /** @var FormatterHelper $formatter */
        $formatter = $this->getHelperSet()->get('formatter');
        $output->writeln('<info>APC/APCu SMA status</info>');
        $formattedLine = $formatter->formatBlock(
            [
                sprintf('%-30s %9u', 'Number segments', $status['sma']['num_seg']),
                sprintf('%-30s %9.2f', 'Segment size', $status['sma']['seg_size'] / 1024 / 1024) . ' MiB',
                sprintf('%-30s %9.2f', 'Available Memory', $status['sma']['avail_mem'] / 1024 / 1024) . ' MiB',
            ],
            'comment'
        );
        $output->writeln($formattedLine);

        $output->writeln('<info>APC/APCu user cache status</info>');
        $formattedLine = $formatter->formatBlock(
            [
                sprintf('%-30s %9u', 'Slots               ', $status['cache']['nslots']),
                sprintf('%-30s %9.2f', 'Memory Size', $status['cache']['mem_size'] / 1024 / 1024) . ' Mib',
                sprintf('%-30s %9u/%u', 'Hits/Misses', $status['cache']['nhits'], $status['cache']['nmisses']),
                sprintf('%-30s %9u (%u/%u)', 'Entries (Inserts/Expunges)', $status['cache']['nentries'], $status['cache']['ninserts'], $status['cache']['nexpunges']),

                sprintf('%-30s %9u', 'Time To Live', $status['cache']['ttl']),
            ],
            'comment'
        );
        $output->writeln($formattedLine);

        if (!isset($status['system'])) {
            return;
        }

        $output->writeln('<info>APC/APCu system cache status</info>');
        $formattedLine = $formatter->formatBlock(
            [
                'Slots               ' . sprintf('%12u', $status['system']['nslots']),
                'Time To Live        ' . sprintf('%12u', $status['system']['ttl']),
                'Hits                ' . sprintf('%12u', $status['system']['nhits']),
                'Misses              ' . sprintf('%12u', $status['system']['nmisses']),
                sprintf('%-8s %9u (%u/%u)', 'Entries (Inserts/Expunges)', $status['system']['nentries'], $status['system']['ninserts'], $status['system']['nexpunges']),
                'Memory Size         ' . sprintf('%12.2f', $status['system']['mem_size'] / 1024 / 1024) . ' Mib',
            ],
            'comment'
        );
        $output->writeln($formattedLine);
    }
}
