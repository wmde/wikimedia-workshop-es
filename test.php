<?php declare(strict_types=1);

use Wikimedia\ES\Statement;
use Wikimedia\ES\Snak;
use Wikimedia\ES\Entity;
use Wikimedia\ES\Id;

require __DIR__ . '/src/autoload.php';
require __DIR__ . '/src/magicalfunctions.php';

// Make Entity
$entity = Entity::make();
echo "Entity made\n";
var_dump($entity->id());

// Make Statement1
$snak1 = new Snak();
$statement1 = Statement::make($snak1);
echo "Statement made, with normal rank\n";
var_dump($statement1->id());

// Make Statement2
$snak2 = new Snak();
$statement2 = Statement::make($snak2);
echo "Statement made, with normal rank\n";
var_dump($statement2->id());
var_dump($statement2->rank());

$statement2->updateRank(Statement::RANK_PREFERRED);
echo "Statement rank set to preffered\n";
var_dump($statement2->rank());

// Dump events into topics?
$eventStore = array_merge(
    $entity->flushRecordedEvents(),
    $statement1->flushRecordedEvents(),
    $statement2->flushRecordedEvents()
);
echo "All events\n";
var_dump($eventStore);

// Recreate from events

function filterEvents( $events, Id $id ) {
    return array_filter( $events, function ( $event ) use ( $id ) {
        return $id->__toString() === $event->aggregateId()->__toString();
    } );
}

echo "Rebuilt from all events\n";
$reEntity = Entity::fromEvents( ...filterEvents( $eventStore, $entity->id() ) );
$reStatement1 = Statement::fromEvents( ...filterEvents( $eventStore, $statement1->id() ) );
$reStatement2 = Statement::fromEvents( ...filterEvents( $eventStore, $statement2->id() ) );

var_dump($reEntity->id());
var_dump($reStatement1->id());
var_dump($reStatement1->rank());
var_dump($reStatement2->id());
var_dump($reStatement2->rank());
