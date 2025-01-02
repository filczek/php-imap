<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Message;

final readonly class MessageAttachment
{
    public function __construct(
        public ?string $id,
        public string $type,
        public string $disposition,
        public ?string $filename,
        public string $content,
    ) {
    }
}
