<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Client\Commands\SelectedState;

use Filczek\PhpImap\Protocol\Client\Commands\ClientCommand;

/** @see https://datatracker.ietf.org/doc/html/rfc9051#section-6.4.5 */
final readonly class FetchCommand implements ClientCommand
{
    public function __construct(
        // TODO SequenceSet DTO exists
        public string $sequence_set,
        public string $attributes,
    ) {
    }

    public function __toString(): string
    {
        // TODO implement ValueObjects that validate this?
        /**
         * > fetch           = "FETCH" SP sequence-set SP ("ALL" / "FULL" / "FAST" / fetch-att / "(" fetch-att *(SP fetch-att) ")")
         * >
         * > fetch-att       = "ENVELOPE" / "FLAGS" / "INTERNALDATE" / "RFC822.SIZE" / "BODY" ["STRUCTURE"] / "UID" / "BODY" section [partial] / "BODY.PEEK" section [partial] / "BINARY" [".PEEK"] section-binary [partial] / "BINARY.SIZE" section-binary
         * >
         * > partial         = "<" number64 "." nz-number64 ">" ; Partial FETCH request. 0-based offset of the first octet, followed by the number of octets in the fragment.
         * >
         * > section         = "[" [section-spec] "]"
         * >
         * > section-binary  = "[" [section-part] "]"
         * >
         * > section-msgtext = "HEADER" / "HEADER.FIELDS" [".NOT"] SP header-list / "TEXT" ; top-level or MESSAGE/RFC822 or MESSAGE/GLOBAL part
         * >
         * > section-part    = nz-number *("." nz-number) ; body part reference. Allows for accessing nested body parts.
         * >
         * > section-spec    = section-msgtext / (section-part ["." section-text])
         * >
         * > section-text    = section-msgtext / "MIME" text other than actual body part (headers, etc.)
         *
         * @see https://datatracker.ietf.org/doc/html/rfc9051#name-formal-syntax
         */
        return "FETCH $this->sequence_set $this->attributes";
    }
}
