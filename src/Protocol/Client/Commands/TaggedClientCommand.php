<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Client\Commands;

use Filczek\PhpImap\Protocol\Client\Tags\Tag;

final readonly class TaggedClientCommand implements ClientCommand
{
    public function __construct(
        public Tag $tag,
        public ClientCommand $command,
    ) {
    }

    public function __toString(): string
    {
        $string = "{$this->tag} {$this->command}";
        /**
         * > All interactions transmitted by client and server are in the form of lines, that is, strings that end with a CRLF.
         *
         * @see https://datatracker.ietf.org/doc/html/rfc9051#section-2.2-2
         */
        $string = "$string\r\n";

        return $string;
    }
}
