<?php declare(strict_types=1);
namespace Wikimedia\ES;

class CommentPublished {

    public function __construct(
        private Message $statement
    ) {
    }

    public function statement(): Message {
        return $this->statement;
    }

}
