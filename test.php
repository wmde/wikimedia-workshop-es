<?php declare(strict_types=1);

use Wikimedia\ES\Statement;
use Wikimedia\ES\Snak;

require __DIR__ . '/src/autoload.php';

$snak = new Snak();

$statement = Statement::make($snak);
var_dump($statement->isAccepted());

$statement->accept();
var_dump($statement->isAccepted());

$events = $statement->flushRecordedEvents();

var_dump($events);

$second = Statement::fromEvents(...$events);

var_dump($second->isAccepted());
