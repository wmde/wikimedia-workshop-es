<?php declare(strict_types=1);
namespace Wikimedia\ES;

class StatementAdded implements \Wikimedia\ES\Event {

    private EntityId $entityId;
    private StatementId $statementId;

    public function __construct(
        EntityId $entityId,
        StatementId $statementId
    ) {
        $this->entityId = $entityId;
        $this->statementId = $statementId;
    }

    public function aggregateId(): EntityId {
        return $this->entityId;
    }

    public function statementId(): StatementId {
        return $this->statementId;
    }

}

