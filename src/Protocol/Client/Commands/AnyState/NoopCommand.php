<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Client\Commands\AnyState;

use Filczek\PhpImap\Protocol\Client\Commands\ClientCommand;

/** @see https://datatracker.ietf.org/doc/html/rfc9051#section-6.1.2 */
final readonly class NoopCommand implements ClientCommand
{
    public function __construct(
    ) {
    }

    public function __toString(): string
    {
        return "NOOP";
    }
}
