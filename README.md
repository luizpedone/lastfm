# Last.fm API Wrapper

This package wraps the last.fm API in a easy to use PHP package. This package is under development at the moment.

## Installation

To install it using composer just execute the following command:

```
composer require luizpedone/lastfm
```

## How to use it

```php
<?php

$lastFm = new \LuizPedone\LastFM\LastFM('your-last-fm-api-key');
$lastFm->limit(10)
    ->page(2)
    ->period(\LuizPedone\LastFM\Period::LAST_MONTH)
    ->getTopArtists($lastFmUser);
```
