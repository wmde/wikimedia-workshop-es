<?php declare(strict_types=1);
namespace Wikimedia\ES;

interface EventLog {

    public function add( Event $event ): void;

    public function getEvents(): array;

}
