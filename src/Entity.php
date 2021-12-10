<?php declare(strict_types=1);
namespace Wikimedia\ES;

use RuntimeException;

class Entity {

    private ?EventLog $eventLog;

    private ?EntityId $id;

    private array $statementIds = [];

    public static function make(EventLog $eventLog): self {
        $entity = new self($eventLog);
        $entity->record(
            new EntityMade(
                new EntityId( "X" . autoInc() )
            )
        );

        return $entity;
    }

    public function __construct( EventLog $eventLog ) {
        $this->eventLog = $eventLog;
    }

    public function addStatement(StatementId $statementId) {
        $this->record(
            new StatementAdded( $this->id, $statementId )
        );
    }

    public static function fromEvents(EventLog $eventLog, Event ...$events): self {
        $entity = new self($eventLog);
        foreach($events as $event) {
            $entity->applyEvent($event);
        }

        return $entity;
    }

    public function id(): ?EntityId {
        return $this->id;
    }

    public function statements(): array {
        return $this->statementIds;
    }

    private function record(Event $event): void {
        $this->eventLog->add($event);
        $this->applyEvent($event);
    }

    private function applyEvent(Event $event): void {
        switch (true) {
            case $event instanceof EntityMade:
                $this->applyEntityMade($event);
                break;

            case $event instanceof StatementAdded:
                $this->applyStatementAdded($event);
                break;
        }
    }

    private function applyEntityMade(EntityMade $event) {
        $this->id = $event->aggregateId();
    }

    private function applyStatementAdded(StatementAdded $event) {
        $this->statementIds[] = $event->statementId();
    }
}
