#!/usr/bin/env php
<?php

set_time_limit(0);

$vendorDir = __DIR__;
$deps = array(
    array('symfony/src/Symfony/Component/ClassLoader', 'https://github.com/symfony/ClassLoader.git', 'origin/master'),
    array('symfony/src/Symfony/Component/Security', 'https://github.com/symfony/Security.git', 'origin/master'),
    array('doctrine-common', 'https://github.com/doctrine/common.git', 'origin/master'),
    array('doctrine-dbal', 'https://github.com/doctrine/dbal.git', 'origin/master')
);

foreach ($deps as $dep) {
    list($name, $url, $rev) = $dep;

    echo "> Installing/Updating $name\n";

    $installDir = $vendorDir.'/'.$name;
    if (!is_dir($installDir)) {
        system(sprintf('git clone -q %s %s', escapeshellarg($url), escapeshellarg($installDir)));
    }

    system(sprintf('cd %s && git fetch -q origin && git reset --hard %s', escapeshellarg($installDir), escapeshellarg($rev)));
}
