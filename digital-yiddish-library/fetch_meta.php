<?php

/**
 * Get https://archive.org/download/ID/ID_meta.xml
 */
require_once 'vendor/autoload.php';

$ids = file('archiveorg_ids.txt');

foreach ($ids as $id) {
    $id = rtrim($id);
    $fname = sprintf('%s_meta.xml', $id);
    $fname_full = 'data/' . $fname;

    if (file_exists($fname_full)) {
        continue;
    }

    $url = sprintf('https://archive.org/download/%s/%s',
                   $id, $fname);
    $meta = file_get_contents($url);
    if (!empty($meta)) {
        file_put_contents($fname_full, $meta);
    }
}