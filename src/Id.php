<?php declare(strict_types=1);
namespace Wikimedia\ES;

interface Id {
    public function __toString(): string;
}
