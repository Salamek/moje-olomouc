# Moje Olomouc PHP API Client

[![Build Status](https://secure.travis-ci.org/Salamek/moje-olomouc.png?branch=master)](https://travis-ci.org/Salamek/moje-olomouc)
[![Coverage](https://codecov.io/gh/Salamek/moje-olomouc/branch/master/graph/badge.svg)](https://codecov.io/gh/Salamek/moje-olomouc)

It's been designed to be object oriented and testable and uses [Guzzle](http://guzzlephp.org) as a
transport layer.

## Install

The recommended way to use MojeOlomouc is [through composer](http://getcomposer.org).

```sh
$ composer require salamek/moje-olomouc
```

## Usage

All API operations are implemented:

* articleCategories
* articles
* eventCategories
* events
* importantMessages
* placeCategories
* places

Usage is as fallows:

```php
use Salamek\MojeOlomouc\MojeOlomouc;
use Salamek\MojeOlomouc\Model\ImportantMessage;

$apiKey = 'YOUR_API_KEY';
$isProduction = false;
$mojeOlomouc = new MojeOlomouc($apiKey, $isProduction);

$importantMessage = new ImportantMessage(
    'Warning',
    new \DateTime('2018-11-10'),
    new \DateTime('2018-11-12')
);

// Create new important-message
$response = $mojeOlomouc->importantMessages->create($importantMessage);
if (!$response->isError())
{
    echo 'SUCCESS'.PHP_EOL;
    print_r($response->getData());
}
else
{
    echo 'ERROR'.PHP_EOL;
    echo $response->getMessage().PHP_EOL;
}

// Update important-message
$importantMessage->setExpireAt(new \DateTime('2018-11-20'));
$response = $mojeOlomouc->importantMessages->update($importantMessage);
if (!$response->isError())
{
    echo 'SUCCESS'.PHP_EOL;
    print_r($response->getData());
}
else
{
    echo 'ERROR'.PHP_EOL;
    echo $response->getMessage().PHP_EOL;
}

// Delete important-message
$response = $mojeOlomouc->importantMessages->delete($importantMessage);
if (!$response->isError())
{
    echo 'SUCCESS'.PHP_EOL;
    print_r($response->getData());
}
else
{
    echo 'ERROR'.PHP_EOL;
    echo $response->getMessage().PHP_EOL;
}

// Or
$response = $mojeOlomouc->importantMessages->delete(null, $importantMessage->getId());
if (!$response->isError())
{
    echo 'SUCCESS'.PHP_EOL;
    print_r($response->getData());
}
else
{
    echo 'ERROR'.PHP_EOL;
    echo $response->getMessage().PHP_EOL;
}

```

## License

This project is licensed under the [LGPL-3.0 license](https://opensource.org/licenses/LGPL-3.0).




