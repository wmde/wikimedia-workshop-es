<?php declare(strict_types=1);
namespace Wikimedia\ES;

class StatementRankUpdated implements \Wikimedia\ES\Event {

    private StatementId $id;
    private string $rank;

    public function __construct(
        StatementId $id,
        string $rank
    ) {
        $this->id = $id;
        $this->rank = $rank;
    }

    public function id(): StatementId {
        return $this->id;
    }

    public function rank(): string {
        return $this->rank;
    }

}
