<?php declare(strict_types=1);
namespace Wikimedia\ES;

class EventStore implements EventLog {

    private $events = [];

    public function add( Event $event ): void {
        $this->events[] = $event;
    }

    public function getEvents(): array {
        return $this->events;
    }

}
