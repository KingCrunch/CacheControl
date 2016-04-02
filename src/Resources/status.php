<?php
namespace Crunch\CacheControl;

const OPCACHE = 'Zend OPcache';
const APCU = 'apcu';
const APC = 'apc';

$handler = [];

if (extension_loaded(OPCACHE)) {
    $handler[] = OPCACHE;
}
if (extension_loaded(APCU)) {
    $handler[] = APCU;
} elseif (extension_loaded(APC)) {
    $handler[] = APC;
}

$result = [];
if (in_array(OPCACHE, $handler, true)) {
    $result[OPCACHE] = opcache_get_status(false);
}
if (in_array(APC, $handler, true)) {
    $result[APC] = [
        'cache'  => apc_cache_info('user', true),
        'system' => apc_cache_info('system', true),
        'sma'    => apc_sma_info(true),
    ];
}
if (in_array(APCU, $handler, true)) {
    $result[APCU] = [
        'cache' => apcu_cache_info(true),
        'sma'   => apcu_sma_info(true),
    ];
}

header('CONTENT-TYPE: text/plain');
echo serialize($result);
