<?php declare(strict_types=1);
namespace Wikimedia\ES;

use RuntimeException;

class Statement {

    private ?EventLog $eventLog;
    private ?StatementId $id;
    private ?Snak $snak;
    private string $rank = self::RANK_NORMAL;

    const RANK_PREFERRED = 'preferred';
    const RANK_NORMAL = 'normal';
    const RANK_DEPRECATED = 'deprecated';

    public static function make(EventLog $eventLog, Snak $snak): self {
        $statement = new self($eventLog);
        $statement->record(
            new StatementMade(
                new StatementId( uniqId('', true) ),
                $snak
            )
        );

        return $statement;
    }

    public function __construct( EventLog $eventLog ) {
        $this->eventLog = $eventLog;
    }

    public static function fromEvents(EventLog $eventLog, Event ...$events): self {
        $statement = new self($eventLog);
        foreach($events as $event) {
            $statement->applyEvent($event);
        }

        return $statement;
    }

    public function updateRank( string $rank ): void {
        if ($this->id === null) {
            throw new RuntimeException('Not properly instantiated??');
        }

        if ($this->snak === null) {
            throw new RuntimeException('Cannot accept empty snak statement');
        }

        if ($this->rank === $rank) {
            throw new RuntimeException('Already at rank ' . $rank);
        }

        $this->record(
            new StatementRankUpdated(
                $this->id,
                $rank
            )
        );
    }

    public function id(): ?StatementId {
        return $this->id;
    }

    public function snak(): ?Snak {
        return $this->snak;
    }

    public function rank(): string {
        return $this->rank;
    }

    private function record(Event $event): void {
        $this->eventLog->add($event);
        $this->applyEvent($event);
    }

    private function applyEvent(Event $event): void {
        switch (true) {
            case $event instanceof StatementMade:
                $this->applyStatementMade($event);
                break;

            case $event instanceof StatementRankUpdated:
                $this->applyStatementRankUpdated($event);
                break;
        }
    }

    private function applyStatementMade(StatementMade $event) {
        $this->id = $event->aggregateId();
        $this->snak = $event->snak();
    }

    private function applyStatementRankUpdated(StatementRankUpdated $event) {
        $this->rank = $event->rank();
    }
}
