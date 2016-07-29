<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

try {
    require __DIR__.'/app/common.php';
    $container['router']->dispatch();
} catch (Exception $e) {
    echo 'Internal Error: '.$e->getMessage();
}
