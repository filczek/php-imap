<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Client\Commands;

final readonly class RawCommand implements ClientCommand
{
    public function __construct(
        public string $string,
    ) {
    }

    public function __toString(): string
    {
        return $this->string;
    }
}
