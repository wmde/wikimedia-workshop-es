<?php declare(strict_types=1);

use Wikimedia\ES\Statement;
use Wikimedia\ES\Snak;
use Wikimedia\ES\Entity;
use Wikimedia\ES\EventStore;
use Wikimedia\ES\Id;

require __DIR__ . '/src/autoload.php';
require __DIR__ . '/src/magicalfunctions.php';

$eventLog = new EventStore();

// Make Entity
$entity = Entity::make($eventLog);
echo "Entity made\n";
var_dump($entity->id());

// Make Statement1
$snak1 = new Snak();
$statement1 = Statement::make($eventLog, $snak1);
echo "Statement made, with normal rank\n";
var_dump($statement1->id());

// Make Statement2
$snak2 = new Snak();
$statement2 = Statement::make($eventLog, $snak2);
echo "Statement made, with normal rank\n";
var_dump($statement2->id());
var_dump($statement2->rank());

$statement2->updateRank(Statement::RANK_PREFERRED);
echo "Statement rank set to preffered\n";
var_dump($statement2->rank());

// Add Statement1 to Entity
$entity->addStatement($statement1->id());
echo "Added Statement1 to Entity\n";
var_dump($entity->statements());

echo "All events\n";
var_dump($eventLog->getEvents());

// Recreate from events

function filterEvents( $eventLog, Id $id ) {
    return array_filter( $eventLog->getEvents(), function ( $event ) use ( $id ) {
        return $id->__toString() === $event->aggregateId()->__toString();
    } );
}

echo "Rebuilt from all events\n";
$reEntity = Entity::fromEvents( $eventLog, ...filterEvents( $eventLog, $entity->id() ) );
$reStatement1 = Statement::fromEvents( $eventLog, ...filterEvents( $eventLog, $statement1->id() ) );
$reStatement2 = Statement::fromEvents( $eventLog, ...filterEvents( $eventLog, $statement2->id() ) );

var_dump($reEntity->id());
var_dump($reEntity->statements());
var_dump($reStatement1->id());
var_dump($reStatement1->rank());
var_dump($reStatement2->id());
var_dump($reStatement2->rank());
