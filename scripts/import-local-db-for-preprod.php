<?php

echo "...Start micro time. \r\n";

require __DIR__ . '/../../website/vendor/autoload.php';

use Dotenv\Dotenv;

// Only allowed for cli
if (PHP_SAPI !== 'cli') {
    die('Not allowed');
}


$start = microtime(true);

// Load .env data
$dotenv = Dotenv::createMutable(__DIR__.'/..', '.env');
$dotenv->safeLoad();

$filename = __DIR__ . '/../database/' . '240121-bcrampon-preprod_2024-01-28.sql';

if (file_exists($filename)) {
    echo 'Filename path: ' . $filename . ".\r\n";
} else {
    echo 'Filename path unknown';
    die();
}

// Import db in preprod server
exec("mysqldump --host=" . getenv('DATABASE_PREPROD_HOST') . " --user=" . getenv('DATABASE_PREPROD_USER') . " --password=" . getenv('DATABASE_PREPROD_PASSWORD') . " --single-transaction --routines --no-tablespaces " . getenv('DATABASE_PROD_NAME') . " > $filename");

echo "...SQL file available on remote server. \r\n";

if (file_exists($filename)) {
    echo "...SQL file copied locally.\r\n";

    $pdo = new PDO(
        'mysql:host=' . getenv('DATABASE_PREPROD_HOST'),
        getenv('DATABASE_PREPROD_USER'),
        getenv('DATABASE_PREPROD_PASSWORD'),
        [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // Regenerate database
    $pdo->exec('DROP DATABASE IF EXISTS `' . getenv('DATABASE_PREPROD_NAME') . '`;');
    $pdo->exec('CREATE DATABASE `' . getenv('DATABASE_PREPROD_NAME') . '`;');
    $pdo->exec('USE `' . getenv('DATABASE_PREPROD_NAME') . '`;');

    echo "...database created.\r\n";

    // Load sql file in local database
    $sql = file_get_contents($filename);
    $pdo->exec($sql);

    // Update some values in tables to work on localhost
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->query('SELECT * FROM wp_options WHERE option_name="active_plugins"');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $active_plugins = unserialize($result["option_value"]);
        unset($active_plugins[array_search("jetpack/jetpack.php", $active_plugins)]);
        $active_plugins = array_values($active_plugins);
        $serialized_array = serialize($active_plugins);

        $stmt = $pdo->prepare('UPDATE wp_options SET option_value = replace(option_value, ?, ?) WHERE option_name = "active_plugins";');
        $stmt->execute([$result["option_value"], $serialized_array]);

        $stmt = $pdo->prepare('UPDATE wp_posts SET guid = replace(guid, ?, ?);');
        $stmt->execute([getenv('WP_LOCAL_SITEURL'), getenv('PREPROD_SITEURL')]);

        $stmt = $pdo->prepare('UPDATE wp_posts SET post_content = replace(post_content, ?, ?);');
        $stmt->execute([getenv('WP_LOCAL_SITEURL'), getenv('PREPROD_SITEURL')]);

        $stmt = $pdo->prepare('UPDATE wp_links SET link_url = replace(link_url, ?, ?);');
        $stmt->execute([getenv('WP_LOCAL_SITEURL'), getenv('PREPROD_SITEURL')]);

        $stmt = $pdo->prepare('UPDATE wp_links SET link_image = replace(link_image, ?, ?);');
        $stmt->execute([getenv('WP_LOCAL_SITEURL'), getenv('PREPROD_SITEURL')]);

        $stmt = $pdo->prepare('UPDATE wp_postmeta SET meta_value = replace(meta_value, ?, ?);');
        $stmt->execute([getenv('WP_LOCAL_SITEURL'), getenv('PREPROD_SITEURL')]);

        $stmt = $pdo->prepare('UPDATE wp_usermeta SET meta_value = replace(meta_value, ?, ?);');
        $stmt->execute([getenv('WP_LOCAL_SITEURL'), getenv('PREPROD_SITEURL')]);

        $stmt = $pdo->prepare('UPDATE wp_options SET option_value = replace(option_value, ?, ?) WHERE option_name = "home" OR option_name = "siteurl";');
        $stmt->execute([rtrim(getenv('WP_LOCAL_SITEURL'), '/'), rtrim(getenv('PREPROD_SITEURL'), '/')]);

        $pdo->commit();

        echo "...database updated.\r\n";
    } catch (Exception $e) {
        if (isset($pdo)) {
            $pdo->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    echo "Error while communicating with remote server.\r\n";
}

echo 'execution time ' . round(microtime(true) - $start, 2) . ' seconds.';