<?php declare(strict_types=1);
namespace Wikimedia\ES;

use RuntimeException;

class Entity {

    private ?EntityId $id;

    private array $statementIds = [];

    private array $recordedEvents = [];

    public static function make(): self {
        $entity = new self();
        $entity->record(
            new EntityMade(
                new EntityId( "X" . autoInc() )
            )
        );

        return $entity;
    }

    public function addStatement(StatementId $statementId) {
        $this->record(
            new StatementAdded( $this->id, $statementId )
        );
    }

    public static function fromEvents(Event ...$events): self {
        $entity = new self();
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

    public function flushRecordedEvents(): array {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];

        return $events;
    }

    private function record(Event $event): void {
        $this->recordedEvents[] = $event;
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
