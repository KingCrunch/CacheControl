Crunch\CacheControl
===================
[![Build Status](https://travis-ci.org/KingCrunch/CacheControl.svg?branch=master)](https://travis-ci.org/KingCrunch/CacheControl)

Control over the APC/OpCache in FPM-instances.

* [List of available packages at packagist.org](http://packagist.org/packages/crunch/cache-control)
* [List of available PHAR distributions](https://github.com/KingCrunch/CacheControl/releases)

Interact with shared-memory (opcode-)caches of an FPM-instances directly. It is completely independent
from any webserver, or application.

Usage
=====

```bash
bin/cache-control clear [--host <hostname|socket-path>]
bin/cache-control status [--host <hostname|socket-path>]
```

Notes
=====

* Only works for local FPM-instances.

Requirements
============
* PHP => ^7.0.0
