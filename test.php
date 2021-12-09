<?php declare(strict_types=1);

use Wikimedia\ES\Statement;
use Wikimedia\ES\Snak;
use Wikimedia\ES\Entity;

require __DIR__ . '/src/autoload.php';

// Make Entity
$entity = Entity::make();
echo "Entity made\n";
var_dump($entity->id());

// Make Statement
$snak = new Snak();

$statement = Statement::make($snak);
echo "Statement made, with normal rank\n";
var_dump($statement->id());
var_dump($statement->rank());

$statement->updateRank(Statement::RANK_PREFERRED);
echo "Statement rank set to preffered\n";
var_dump($statement->rank());

// Dump events into topics?
$eventStore = [
    'entity' => $entity->flushRecordedEvents(),
    'statement' => $statement->flushRecordedEvents(),
];
echo "All events\n";
var_dump($eventStore);

// Recreate from events
echo "Rebuilt from all events\n";
$reEntity = Entity::fromEvents(...$eventStore['entity']);
$reStatement = Statement::fromEvents(...$eventStore['statement']);

var_dump($reEntity->id());
var_dump($reStatement->id());
var_dump($reStatement->rank());
