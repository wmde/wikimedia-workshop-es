<?php declare(strict_types=1);
namespace Wikimedia\ES;

class StatementRejected {

    private $id;

    public function __construct( StatementId $id ) {
        $this->id = $id;
    }

    public function id(): StatementId {
        return $this->id;
    }

}
