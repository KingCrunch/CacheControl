<?php
declare(strict_types=1);
namespace Crunch\CacheControl\Console\Helper;

use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Output\OutputInterface;

class CacheControlHelper extends Helper
{
    public function getName(): string
    {
        return 'cache-control';
    }

    public function printOpcacheStatus(array $status, OutputInterface $output)
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

    public function printApcuStatus(array $status, OutputInterface $output)
    {
        /** @var FormatterHelper $formatter */
        $formatter = $this->getHelperSet()->get('formatter');
        $output->writeln('<info>APCu SMA status</info>');
        $formattedLine = $formatter->formatBlock(
            [
                sprintf('%-30s %9u', 'Number segments', $status['sma']['num_seg']),
                sprintf('%-30s %9.2f', 'Segment size', $status['sma']['seg_size'] / 1024 / 1024) . ' MiB',
                sprintf('%-30s %9.2f', 'Available Memory', $status['sma']['avail_mem'] / 1024 / 1024) . ' MiB',
            ],
            'comment'
        );
        $output->writeln($formattedLine);

        $output->writeln('<info>APCu cache status</info>');
        $formattedLine = $formatter->formatBlock(
            [
                sprintf('%-30s %9u', 'Slots', $status['cache']['num_slots']),
                sprintf('%-30s %9u', 'Time To Live (TTL)', $status['cache']['ttl']),
                sprintf('%-30s %9u/%u', 'Hits/Misses', $status['cache']['num_hits'], $status['cache']['num_misses']),
                sprintf('%-30s %9u', 'Inserts', $status['cache']['num_inserts']),
                sprintf('%-30s %9u', 'Entries', $status['cache']['num_entries']),
                sprintf('%-30s %9f', 'Expunges', $status['cache']['expunges']),
                sprintf('%-29s %s', 'Start', date('c', $status['cache']['start_time'])),
                sprintf('%-30s %9.2f', 'Memory size', $status['cache']['mem_size'] / 1024) . ' KiB',
                sprintf('%-30s %s', 'Type', $status['cache']['memory_type'])
            ],
            'comment'
        );
        $output->writeln($formattedLine);
    }
}
