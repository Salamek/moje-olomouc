# PHP MojeOlomouc

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
$response->getData();

// Update important-message
$importantMessage->setExpireAt(new \DateTime('2018-11-20'));
$response = $mojeOlomouc->importantMessages->update($importantMessage);
$response->getData();

// Delete important-message
$response = $mojeOlomouc->importantMessages->delete($importantMessage);
$response->getData();

// Or
$response = $mojeOlomouc->importantMessages->delete(null, $importantMessage->getId());
$response->getData();

```

##License

This project is licensed under the [LGPL-3.0 license](https://opensource.org/licenses/LGPL-3.0).




