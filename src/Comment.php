<?php declare(strict_types=1);
namespace Wikimedia\ES;

use RuntimeException;

class Comment {

    private ?CommentId $id;
    private ?Message $message;
    private bool $accepted = false;

    private array $recordedEvents = [];

    public static function make(Message $message): self {
        $comment = new self();
        $comment->record(
            new CommentMade(
                new CommentId(),
                $message
            )
        );

        return $comment;
    }

    public static function fromEvents(Event ...$events): self {
        $comment = new self();
        foreach($events as $event) {
            $comment->applyEvent($event);
        }

        return $comment;
    }

    public function accept(): void {
        if ($this->id === null) {
            throw new RuntimeException('Not properly instantiated??');
        }

        if ($this->message === null) {
            throw new RuntimeException('Cannot accept empty message comment');
        }

        if ($this->accepted === true) {
            throw new RuntimeException('Already accepted');
        }

        $this->record(
            new CommentAccepted(
                $this->id
            )
        );
    }

    public function id(): ?CommentId {
        return $this->id;
    }

    public function message(): ?Message {
        return $this->message;
    }

    public function isAccepted(): bool {
        return $this->accepted;
    }

    public function flushRecordedEvents(): array {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];

        return $events;
    }

    private function record(Event $event): void {
        $this->recordedEvents[] = $event;
        $this->applyEvent($event);
    }

    private function applyEvent(Event $event): void {
        switch (true) {
            case $event instanceof CommentMade:
                $this->applyCommentMade($event);
                break;

            case $event instanceof CommentAccepted:
                $this->applyCommentAccepted($event);
                break;
        }
    }

    private function applyCommentMade(CommentMade $event) {
        $this->id = $event->id();
        $this->message = $event->message();
    }

    private function applyCommentAccepted(CommentAccepted $event) {
        $this->accepted = true;
    }
}
