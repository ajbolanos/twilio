<?php
$srcDir = __DIR__;
$baseDir   = dirname($srcDir);

require_once $srcDir . '/composer/ClassLoader.php';
require_once $baseDir .'/config.php';

// ClassLoader implements a PSR-0 class loader
$loader = new \Composer\Autoload\ClassLoader();
$loader->set('Twilio', array($srcDir . '/twilio/src'));
$loader->register(true);
return $loader;
