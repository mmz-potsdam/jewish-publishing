<?php

/**
 * Extract archive.org identifiers from search results
 *
 * For every identifier
 *  e.g. nybc200507
 * you can open the results page at
 *  https://www.yiddishbookcenter.org/collections/yiddish-books/spb-ID
 * e.g https://www.yiddishbookcenter.org/collections/yiddish-books/spb-nybc200507
 * or access the information on archive.org at
 *  https://archive.org/download/ID/
 * e.g. https://archive.org/download/nybc200507/
 *
 * Metadata at
 *  https://archive.org/download/ID/ID_meta.xml
 * e.g. https://archive.org/download/nybc200507/nybc200507_meta.xml
 *
 * MARC-XML at
 *  https://archive.org/download/ID/ID_marc.xml
 * e.g. https://archive.org/download/nybc200507/nybc200507_marc.xml
 *
 */
require_once 'vendor/autoload.php';

use Goutte\Client;

$client = new Client();

$url = "https://www.yiddishbookcenter.org/search/collection/Yiddish%20Book%20Center's%20Spielberg%20Digital%20Yiddish%20Library?query=&restrict=&page=";

for ($page = 0; $page <= 592; $page++) {
    $crawler = $client->request('GET', $url . $page);

    $crawler->filter('a.search-result.search-result--yiddish')->each(function ($node) {
        $href = $node->attr('href');
        if (preg_match('#/spb-([^\/]+)/#', $href, $matches)) {
            echo $matches[1] . "\n";
        }
        else {
            die('Link not in expected format: ' . $href);
        }
    });
}
