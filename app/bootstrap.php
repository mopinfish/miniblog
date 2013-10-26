<?php

if (file_exists($loaderFile = dirname(__FILE__) . '/core/ClassLoader.php')) {
    require_once $loaderFile;
    $loader = new ClassLoader();
    $loader->registerDir(dirname(__FILE__) . '/core');
    $loader->registerDir(dirname(__FILE__) . '/models');
    $loader->registerDir(dirname(__FILE__) . '/models/db');
    $loader->registerDir(dirname(__FILE__) . '/models/biz');
    $loader->register();
}
if (file_exists($twigLoaderFile = dirname(__FILE__) . '/vendor/Twig/Autoloader.php')) {
    require_once $twigLoaderFile;
    Twig_Autoloader::register();
}
