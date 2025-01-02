<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Server\Responses;

/**
 * @see https://datatracker.ietf.org/doc/html/rfc9051#section-7.6
 * @see https://datatracker.ietf.org/doc/html/rfc9051#section-9 continue-req
 */
final readonly class CommandContinuationRequest implements ServerResponse
{
    public function __construct(
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
