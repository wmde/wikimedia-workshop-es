<?php declare(strict_types=1);
namespace Wikimedia\ES;

class CommentAccepted implements \Wikimedia\ES\Event {

    public function __construct(
        private CommentId $id
    ) {
    }

    public function id(): CommentId {
        return $this->id;
    }

}
