<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Server\Concerns;

use Filczek\PhpImap\StreamV2\AuthenticateMethod;

interface ManagesAuthentication
{
    public function isAuthenticated(): bool;

    public function reauthenticate(): void;

    public function authenticate(AuthenticateMethod $method): void;

    public function logout(): void;
}
