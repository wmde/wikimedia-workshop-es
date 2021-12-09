<?php declare(strict_types=1);
namespace Wikimedia\ES;

use RuntimeException;

class Entity {

    private ?EntityId $id;
    private ?array $statementIds;

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

    public static function fromEvents(Event ...$events): self {
        $entity = new self();
        foreach($events as $event) {
            $entity->applyEvent($event);
        }

        return $entity;
    }

    public function aggregateId(): ?EntityId {
        return $this->id;
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
        }
    }

    private function applyEntityMade(EntityMade $event) {
        $this->id = $event->aggregateId();
    }
}
