<?php

require_once 'vendor/autoload.php';

use Lavibi\Popoya;

$chain = new Popoya\ValidatorChain();
$chain->isInteger();

var_dump($chain->isValid('5'));
var_dump($chain->getMessage());

var_dump($chain->isValid('5a'));
var_dump($chain->getMessage());

var_dump($chain->isValid('0'));
var_dump($chain->getMessage());

var_dump($chain->isValid('-4343'));
var_dump($chain->getMessage());
