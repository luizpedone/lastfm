# Last.fm API Wrapper

This package wraps the last.fm API in a easy to use PHP package.

### How to use it

```php
<?php

$lastFm = new \LuizPedone\LastFM\LastFM('your-last-fm-api-key');
$lastFmUser = 'luiz-pedone';
$lastFm->getTopArtists($lastFmUser);
```