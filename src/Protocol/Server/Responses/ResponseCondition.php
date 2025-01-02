<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Server\Responses;

/**
 * @see https://datatracker.ietf.org/doc/html/rfc9051#section-7.1-1
 * @see https://datatracker.ietf.org/doc/html/rfc9051#section-9 resp-cond-state
 * @see https://datatracker.ietf.org/doc/html/rfc9051#section-9 resp-cond-auth
 * @see https://datatracker.ietf.org/doc/html/rfc9051#section-9 resp-cond-bye
 */
enum ResponseCondition: string
{
    case Ok = "OK";
    case No = "NO";
    case Bad = "BAD";
    case PreAuth = "PREAUTH";
    case Bye = "BYE";

    public function matches(self ...$statuses): bool
    {
        return in_array($this, $statuses, true);
    }

    public function notMatches(self ...$statuses): bool
    {
        return false === $this->matches(...$statuses);
    }
}
