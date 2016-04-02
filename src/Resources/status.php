<?php
namespace Crunch\CacheControl;

const OPCACHE = 'Zend OPcache';
const APCU = 'apcu';

$handler = [];

if (extension_loaded(OPCACHE)) {
    $handler[] = OPCACHE;
}
if (extension_loaded(APCU)) {
    $handler[] = APCU;
}

$result = [];
if (in_array(OPCACHE, $handler, true)) {
    $result[OPCACHE] = opcache_get_status(false);
}
if (in_array(APCU, $handler, true)) {
    $result[APCU] = [
        'cache' => apcu_cache_info(true),
        'sma'   => apcu_sma_info(true),
    ];
}

header('CONTENT-TYPE: text/plain');
echo serialize($result);
