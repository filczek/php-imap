<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Client\Tags;

use Stringable;

/**
 * The client command identifier, also known as "tag".
 *
 * @see https://datatracker.ietf.org/doc/html/rfc9051#section-2.2.1-1
 */
interface Tag extends Stringable
{
    /** Returns the next unique command tag. */
    public function next(): static;
}
