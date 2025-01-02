<?php

declare(strict_types=1);

namespace Filczek\PhpImap;

use Filczek\PhpImap\Protocol\Client\CommandBus\ClientCommandBus;
use Filczek\PhpImap\Protocol\Client\Commands\AuthenticatedState\SelectCommand;
use Filczek\PhpImap\Protocol\Client\Commands\ClientCommand;
use Filczek\PhpImap\Protocol\Server\Responses\ResponseCondition;
use Filczek\PhpImap\Protocol\Server\Responses\ServerResponse;
use Filczek\PhpImap\Protocol\Server\Responses\TaggedServerResponse;
use Filczek\PhpImap\Protocol\Server\ServerConnection;
use RuntimeException;

// TODO this is Proof-of-Concept stage, refactor
final readonly class ImapServer
{
    public function __construct(
        private ServerConnection $connection,
        private ClientCommandBus $command_bus,
    ) {
    }

    public function connectTo(string $address): void
    {
        $this->connection->connectTo($address);
    }

    public function executeCommand(ClientCommand $command): ServerResponse
    {
        return $this->command_bus->executeCommand($command);
    }

    public function mailbox(string $name): Mailbox
    {
        $response = $this->executeCommand(new SelectCommand($name));
        if (false === $response instanceof TaggedServerResponse) {
            throw new RuntimeException($response->message());
        }

        if ($response->status->notMatches(ResponseCondition::Ok)) {
            throw new RuntimeException($response->message());
        }

        return new Mailbox(
            mailbox: $name,
            server: $this,
        );
    }
}
