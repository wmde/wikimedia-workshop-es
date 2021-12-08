<?php declare(strict_types=1);

use Wikimedia\ES\Comment;
use Wikimedia\ES\Message;

require __DIR__ . '/src/autoload.php';

$message = new Message();

$comment = Comment::make($message);
var_dump($comment->isAccepted());

$comment->accept();
var_dump($comment->isAccepted());

$events = $comment->flushRecordedEvents();

var_dump($events);

$second = Comment::fromEvents(...$events);

var_dump($second->isAccepted());
