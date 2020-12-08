Kerb Partner SDK PHP
==


```php
<?php

use Kerb\Partner\Partner;
use Kerb\Partner\Requests\Ping;

Partner::setApiKey($token);

$options = [
    'body' => [],
];
$response = Partner::send('ping', $options);


$request = new Ping($options);
/// this is same with above
// $request = Partner::makeRequest('ping', $options);

$request->setHeader('testing', 'ok');
$request->setHeader('not-used', null);

$response = Partner::request($request);

```
