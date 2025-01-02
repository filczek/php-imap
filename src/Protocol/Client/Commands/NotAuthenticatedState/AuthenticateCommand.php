<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Client\Commands\NotAuthenticatedState;

use Filczek\PhpImap\Protocol\Client\Commands\ClientCommand;

/** @see https://datatracker.ietf.org/doc/html/rfc9051#section-6.2.2 */
final readonly class AuthenticateCommand implements ClientCommand
{
    public function __construct(
        public string $auth_type,
        public ?string $initial_response,
    ) {
    }

    public function __toString(): string
    {
        // TODO implement ValueObjects that validate, quote or not?
        /**
         * > authenticate    = "AUTHENTICATE" SP auth-type [SP initial-resp] (CRLF base64)
         * >
         * > auth-type       = atom ; Authentication mechanism name, as defined by [SASL], Section 7.1
         * >
         * > initial-resp    =  (base64 / "=") ; "initial response" defined in Section 4 of [SASL]
         *
         * @see https://datatracker.ietf.org/doc/html/rfc9051#name-formal-syntax
         */

        $arguments = "$this->auth_type $this->initial_response";
        $arguments = trim($arguments);

        return "AUTHENTICATE $arguments";
    }
}
