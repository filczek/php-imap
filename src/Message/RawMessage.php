<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Message;

use Stringable;

final readonly class RawMessage implements Stringable
{
    /** @param string[] $lines */
    public static function fromLines(array $lines): self
    {
        return new self($lines);
    }

    private function __construct(
        /** @var string[] $lines */
        private array $lines,
    ) {
    }

    public function toString(): string
    {
        return (string) $this;
    }

    public function __toString(): string
    {
        return implode("", $this->lines);
    }
}
