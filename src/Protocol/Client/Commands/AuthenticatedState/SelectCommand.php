<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Client\Commands\AuthenticatedState;

use Filczek\PhpImap\Protocol\Client\Commands\ClientCommand;

/** @see https://datatracker.ietf.org/doc/html/rfc9051#section-6.3.2 */
final readonly class SelectCommand implements ClientCommand
{
    public function __construct(
        public string $mailbox_name,
    ) {
    }

    public function __toString(): string
    {
        // TODO implement ValueObjects that validate, quote or not?
        /**
         * > select          = "SELECT" SP mailbox
         * >
         * > mailbox         = "INBOX" / astring ; INBOX is case insensitive. All case variants of INBOX (e.g., "iNbOx")
         * >                                       MUST be interpreted as INBOX, not as an astring. An astring that
         * >                                       consists of the case-insensitive sequence "I" "N" "B" "O" "X" is
         * >                                       considered to be an INBOX and not an astring. Refer to Section 5.1
         * >                                       for further semantic details of mailbox names.
         *
         * @see https://datatracker.ietf.org/doc/html/rfc9051#name-formal-syntax
         */
        return "SELECT \"$this->mailbox_name\"";
    }
}
