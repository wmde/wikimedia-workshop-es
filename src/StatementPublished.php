<?php declare(strict_types=1);
namespace Wikimedia\ES;

class StatementPublished {

    private $statement;

    public function __construct(
        Snak $statement
    ) {
        $this->$statement = $statement;
    }

    public function statement(): Snak {
        return $this->statement;
    }

}
