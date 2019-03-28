# Popoya

Popoya -> Poppoya

A simple PHP validator library.

[![Build Status](https://travis-ci.org/lavibi/popoya.svg?branch=master)](https://travis-ci.org/lavibi/popoya) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lavibi/popoya/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lavibi/popoya/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/lavibi/popoya/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/lavibi/popoya/?branch=master)

## Single Validator

Single validator

```php
use Lavibi\Popoya;

$sameValidator = new Popoya\Same();

$sameValidator->setOptions['
    'compared_value' => 5
'];

$sameValidator->isValid(5);

```

Options can be added via readable method

```php
use Lavibi\Popoya;

$sameValidator = new Popoya\Same();

$sameValidator->sameAs(5); // set options compared_value = 5

$sameValidator->isValid(5);
```

## Validator chain

Validate value with more than one validator

```php
$chainValidator = new Popoya\ValidatorChain();

$chainValidator->addValidator((new Popoya\Same())->setOptions(...));
$chainValidator->addValidator((new Popoya\NotSame())->setOptions(...));

$chainValidator->isValid(5);
```
