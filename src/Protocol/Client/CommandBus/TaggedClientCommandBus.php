<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Client\CommandBus;

use Filczek\PhpImap\Protocol\Client\Commands\ClientCommand;
use Filczek\PhpImap\Protocol\Client\Commands\TaggedClientCommand;
use Filczek\PhpImap\Protocol\Client\Tags\ManagesTag;
use Filczek\PhpImap\Protocol\Server\Responses\ServerResponse;
use Filczek\PhpImap\Protocol\Server\ServerConnection;

final readonly class TaggedClientCommandBus implements ClientCommandBus
{
    public function __construct(
        private ServerConnection $connection,
        private ManagesTag       $tag,
    ) {
    }

    public function executeCommand(ClientCommand $command): ServerResponse
    {
        $tag = $this->tag->current();
        $tagged_command = new TaggedClientCommand($tag, $command);

        $this->tag->advance();

        return $this->connection->executeCommand($tagged_command);
    }
}
