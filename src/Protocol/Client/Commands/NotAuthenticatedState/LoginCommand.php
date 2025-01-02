<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Client\Commands\NotAuthenticatedState;

use Filczek\PhpImap\Protocol\Client\Commands\ClientCommand;

/** @see https://datatracker.ietf.org/doc/html/rfc9051#section-6.2.3 */
final readonly class LoginCommand implements ClientCommand
{
    public function __construct(
        public string $username,
        public string $password,
    ) {
    }

    public function __toString(): string
    {
        // TODO implement ValueObjects that validate, quote or not?
        /**
         * > login           = "LOGIN" SP userid SP password
         * >
         * > userid          = astring
         * >
         * > password        = astring
         *
         * @see https://datatracker.ietf.org/doc/html/rfc9051#name-formal-syntax
         */
        return "LOGIN \"$this->username\" \"$this->password\"";
    }
}
