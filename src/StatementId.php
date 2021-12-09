<?php declare(strict_types=1);
namespace Wikimedia\ES;

class StatementId implements Id{

    private $string;

    public function __construct( string $string ) {
        $this->string = $string;
    }

	function __toString(): string {
        return "statement:{$this->string}";
	}
}
