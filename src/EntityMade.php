<?php declare(strict_types=1);
namespace Wikimedia\ES;

class EntityMade implements \Wikimedia\ES\Event {

    private EntityId $id;

    public function __construct(
        EntityId $id
    ) {
        $this->id = $id;
    }

    public function id(): EntityId {
        return $this->id;
    }

}

