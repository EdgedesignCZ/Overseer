#!/usr/local/bin/php
<?php

namespace Edge\Overseer;

require_once __DIR__ . '/vendor/autoload.php';

define('CONFIG_FILE', __DIR__ . '/config/config.ini');
define('CONFIG_INDEX_EMAIL', 'email');
define('CONFIG_INDEX_IGNORE', 'ignore');
define('CONFIG_INDEX_NAME', 'name');
define('CONFIG_INDEX_WATCHFILE', 'watchfile');

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

if (!file_exists(CONFIG_FILE)) {
    die('Config file "' . CONFIG_FILE . '" is missing. Create it!' . "\n");
}

$config = parse_ini_file(CONFIG_FILE);
if (!array_key_exists(CONFIG_INDEX_NAME, $config)) {
    die('You must specify some "' . CONFIG_INDEX_NAME .'" in config!' . "\n");
}
if (!array_key_exists(CONFIG_INDEX_EMAIL, $config)) {
    die('You must specify some "' . CONFIG_INDEX_EMAIL .'" in config!' . "\n");
}
if (!array_key_exists(CONFIG_INDEX_WATCHFILE, $config)) {
    die('You must specify some "' . CONFIG_INDEX_WATCHFILE . '" in config!' . "\n");
}

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

$checkers = array();
foreach ($config[CONFIG_INDEX_WATCHFILE] as $fileToWatch) {
    echo "Checking file '$fileToWatch'\n";

    $ignoreList = array_key_exists(CONFIG_INDEX_IGNORE, $config) ? $config[CONFIG_INDEX_IGNORE] : array();

    $checker = new FileChecker(
        $config[CONFIG_INDEX_NAME],
        $fileToWatch,
        $config[CONFIG_INDEX_EMAIL],
        $ignoreList
    );
    $checker->check();
}

echo "\nOK\n";
