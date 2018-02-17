# Last.fm API Wrapper

[![Maintainability](https://api.codeclimate.com/v1/badges/0ba948481d444492a2f7/maintainability)](https://codeclimate.com/github/luizpedone/lastfm/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/0ba948481d444492a2f7/test_coverage)](https://codeclimate.com/github/luizpedone/lastfm/test_coverage)

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

$topArtists = $lastFm->user()
    ->period(Period::LAST_MONTH)
    ->limit(10)
    ->getTopArtists('luiz-pedone');
```
