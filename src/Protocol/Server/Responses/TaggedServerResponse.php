<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Server\Responses;

use Filczek\PhpImap\Protocol\Client\Commands\ClientCommand;

/** @see https://datatracker.ietf.org/doc/html/rfc9051#section-9 response-tagged */
final readonly class TaggedServerResponse implements ServerResponse
{
    public function __construct(
        public ClientCommand $command,
        public ResponseCondition $status,
        private string $message,
        /** @var string[] $lines */
        private array $lines,
    ) {
    }

    public function message(): string
    {
        return $this->message;
    }

    /** @inheritDoc */
    public function lines(): array
    {
        return $this->lines;
    }

    public function __toString(): string
    {
        return implode("", $this->lines());
    }
}
