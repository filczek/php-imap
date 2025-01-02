<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Server\Concerns;

interface ManagesConnection
{
    /**
     * Connects to the given server.
     *
     * @param string $address
     * @return void
     */
    public function connectTo(string $address): void;

    /**
     * Reconnects (without authenticating) to the server.
     *
     * @return void
     */
    public function reconnect(): void;

    /**
     * Disconnects from the underlying connection.
     *
     * @return void
     */
    public function disconnect(): void;

    public function address(): string;
}
