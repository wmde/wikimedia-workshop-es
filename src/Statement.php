<?php declare(strict_types=1);
namespace Wikimedia\ES;

use RuntimeException;

class Statement {

    private ?StatementId $id;
    private ?Snak $snak;
    private bool $accepted = false;

    private array $recordedEvents = [];

    public static function make(Snak $snak): self {
        $statement = new self();
        $statement->record(
            new StatementMade(
                new StatementId(),
                $snak
            )
        );

        return $statement;
    }

    public static function fromEvents(Event ...$events): self {
        $statement = new self();
        foreach($events as $event) {
            $statement->applyEvent($event);
        }

        return $statement;
    }

    public function accept(): void {
        if ($this->id === null) {
            throw new RuntimeException('Not properly instantiated??');
        }

        if ($this->snak === null) {
            throw new RuntimeException('Cannot accept empty snak statement');
        }

        if ($this->accepted === true) {
            throw new RuntimeException('Already accepted');
        }

        $this->record(
            new StatementAccepted(
                $this->id
            )
        );
    }

    public function id(): ?StatementId {
        return $this->id;
    }

    public function snak(): ?Snak {
        return $this->snak;
    }

    public function isAccepted(): bool {
        return $this->accepted;
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
            case $event instanceof StatementMade:
                $this->applyStatementMade($event);
                break;

            case $event instanceof StatementAccepted:
                $this->applyStatementAccepted($event);
                break;
        }
    }

    private function applyStatementMade(StatementMade $event) {
        $this->id = $event->id();
        $this->snak = $event->snak();
    }

    private function applyStatementAccepted(StatementAccepted $event) {
        $this->accepted = true;
    }
}
