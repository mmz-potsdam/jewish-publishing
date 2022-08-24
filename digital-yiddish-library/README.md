# Digital Yiddish Library

https://www.yiddishbookcenter.org/collections/digital-yiddish-library contains
more than 11,000 Yiddish titles available to read online or download free of charge.

The content is stored at archive.org.



## Installation

To get the dependencies for the PHP scripts, type

    composer install

## Build archiveorg_ids.txt

    php fetch_archiveorg_ids.php

goes through https://www.yiddishbookcenter.org/search/collection/Yiddish%20Book%20Center's%20Spielberg%20Digital%20Yiddish%20Library
and compiles the list of archive.org ids.

TODO: Read the number of result pages (currently hardwired in `$page <= 592`)
dynamically.

## fetch ID_meta.xml

    php fetch_meta.php

goes through archiveorg_ids.txt and gets for each ID the meta-data stored at
https://archive.org/download/ID/ID_meta.xml

## parse ID_meta.xml

    php parse_meta.php

goes through all the ID_meta.xml in data/ and puts the data into an Excel-sheet.

TODO: the handling of fields with multiple entries could probably be improved
