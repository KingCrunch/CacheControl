<?php
namespace Crunch\CacheControl;

const OPCACHE = 'Zend OPcache';
const APCU = 'apcu';

$handler = array();

if (extension_loaded(OPCACHE)) {
    $handler[] = OPCACHE;
}
if (extension_loaded(APCU)) {
    $handler[] = APCU;
}

$result = array();
if (in_array(OPCACHE, $handler)) {
    $result[OPCACHE] = opcache_reset();
}
if (in_array(APCU, $handler)) {
    $result[APCU] = apcu_clear_cache('user');
}

header('CONTENT-TYPE: text/plain');
echo serialize($result);
