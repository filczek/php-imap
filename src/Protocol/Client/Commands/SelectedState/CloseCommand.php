<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Client\Commands\SelectedState;

use Filczek\PhpImap\Protocol\Client\Commands\ClientCommand;

/** @see https://datatracker.ietf.org/doc/html/rfc9051#section-6.4.1 */
final readonly class CloseCommand implements ClientCommand
{
    public function __construct(
    ) {
    }

    public function __toString(): string
    {
        return "CLOSE";
    }
}
