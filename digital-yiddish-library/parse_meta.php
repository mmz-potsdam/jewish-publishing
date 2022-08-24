<?php

/**
 * Extract book metadata from XML at
 *  https://archive.org/download/ID/ID_meta.xml
 * e.g. https://archive.org/download/nybc200507/nybc200507_meta.xml
 *
 */
require_once 'vendor/autoload.php';

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

$fnameOut = 'digital-yiddish-library-meta.xlsx';

$writer = WriterEntityFactory::createXLSXWriter();
$writer->openToFile($fnameOut); // write data to a file or to a PHP stream


$filenames = glob('data/*_meta.xml');
natsort($filenames);

$header_written = false;

$count = 0;
foreach ($filenames as $fname) {
    // for the following two lines, see https://stackoverflow.com/a/20431742
    $xml = simplexml_load_string(file_get_contents($fname),
                                 'SimpleXMLElement',
                                 LIBXML_NOCDATA);
    $meta = json_decode(json_encode($xml), true);

    /*
    if ('50yorlebn00me' == $meta['identifier']) {
        var_dump($meta);
        exit;
    }
    */

    // pull fields of interest
    $row = [
        'ID' => $meta['identifier'],
        'creator' => extract_first($meta, 'creator'),
        'title' => extract_first($meta, 'title'),
        'title-alt-script' => extract_first($meta, 'title-alt-script'),
        'publisher' => extract_first($meta, 'publisher'),
        'date' => flatten($meta, 'date'),
        'language' => extract_language($meta),
        /*
        'pagination' => array_key_exists('description', $meta)
            && is_array($meta['description'])
            && count($meta['description']) > 2
            ? $meta['description'][2]
            : '',
        */
        'imagecount' => array_key_exists('imagecount', $meta)
            ? $meta['imagecount'] : '',
    ];

    /*
    // check $row - only scalar values
    foreach ($row as $key => $val) {
        if (is_array($val)) {
            var_dump($meta);
            die($key);
        }
    }
    */

    if (!$header_written) {
        // echo join("\t", array_keys($row)) . "\n";
        $writer->addRow(WriterEntityFactory::createRowFromArray(array_keys($row)));
        $header_written = true;
    }


    // echo join("\t", array_values($row)) . "\n";
    $writer->addRow(WriterEntityFactory::createRowFromArray(array_values($row)));

    /*
    if (++$count > 20) {
        break;
    }
    */
}
$writer->close();

function extract_language($meta)
{
    if (!array_key_exists('language', $meta))  {
        return '';
    }

    $language = $meta['language'];

    if (is_array($language)) {
        $language = join('; ', array_unique($language));
    }

    if (preg_match('/^yiddish$/i', $language)) {
        $language = 'yid';
    }

    return $language;
}

function flatten($meta, $key)
{
    $val = array_key_exists($key, $meta) ? $meta[$key] : '';

    if (is_array($val)) {
        $val = join('; ', array_unique($val));
    }

    return $val;
}

function extract_first($meta, $key)
{
    return array_key_exists($key, $meta)
        ? (is_array($meta[$key])
            ? $meta[$key][0]
            : $meta[$key])
        : '';
}