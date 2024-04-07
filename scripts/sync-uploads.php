<?php

require __DIR__ . '/../website/vendor/autoload.php';

use Dotenv\Dotenv;

// Only allowed for cli
if (PHP_SAPI !== 'cli') {
    die('Not allowed');
}

echo "...Start micro time. \r\n";
$start = microtime(true);

// Load .env data
echo "...load .env from: ." . __DIR__. "'/../website', '.env'\r\n";
$dotenv = Dotenv::createImmutable(__DIR__.'/../website', '.env');
$dotenv->safeLoad();

// Copy uploads folder on current machine
echo "Start copying files\r\n";
exec('scp -r ' . getenv('PROD_USER') . '@' . getenv('PROD_HOST') . ':preprod-bcrampon/website/app/uploads website/app/');

echo 'execution time ' . round(microtime(true) - $start, 2) . ' seconds.';
