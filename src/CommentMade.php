<?php declare(strict_types=1);
namespace Wikimedia\ES;

class CommentMade implements \Wikimedia\ES\Event {

    public function __construct(
        private CommentId $id,
        private Message $statement
    ) {
    }

    public function id(): CommentId {
        return $this->id;
    }

    public function message(): Message {
        return $this->statement;
    }


}
