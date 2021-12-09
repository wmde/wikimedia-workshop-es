<?php declare(strict_types=1);
namespace Wikimedia\ES;

class StatementMade implements \Wikimedia\ES\Event {

    private StatementId $id;
    private Snak $statement;

    public function __construct(
        StatementId $id,
        Snak $statement
    ) {
        $this->id = $id;
        $this->statement = $statement;
    }

    public function id(): StatementId {
        return $this->id;
    }

    public function snak(): Snak {
        return $this->statement;
    }


}

