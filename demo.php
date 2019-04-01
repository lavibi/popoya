<?php

require_once 'vendor/autoload.php';

use Lavibi\Popoya;

$chain = new Popoya\ValidatorChain();
$chain->sameAs('5')->sameAs('6');

var_dump($chain);