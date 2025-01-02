<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Message;

use DateTimeImmutable;

// TODO expose more
final readonly class Message
{
    public function __construct(
        public array $headers,
        public DateTimeImmutable $date,
        // Originator fields
        public array $from,
        public array $sender,
        public array $reply_to,
        // Destination fields
        public array $to,
        public array $cc,
        public array $bcc,
        public ?string $subject,
        public ?string $text,
        public ?string $html,
        public array $attachments,
    ) {
    }
}
