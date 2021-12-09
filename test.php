<?php declare(strict_types=1);

use Wikimedia\ES\Statement;
use Wikimedia\ES\Snak;

require __DIR__ . '/src/autoload.php';

$snak = new Snak();

$statement = Statement::make($snak);
echo "Statement made, with normal rank\n";
var_dump($statement->rank());

$statement->updateRank(Statement::RANK_PREFERRED);
echo "Statement rank set to preffered\n";
var_dump($statement->rank());

$events = $statement->flushRecordedEvents();

echo "All events for statement\n";
var_dump($events);

$second = Statement::fromEvents(...$events);

echo "Rank rebuilt from all events\n";
var_dump($second->rank());
