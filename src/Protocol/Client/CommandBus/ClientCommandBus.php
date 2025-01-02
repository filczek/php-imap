<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Client\CommandBus;

use Filczek\PhpImap\Protocol\Client\Commands\ClientCommand;
use Filczek\PhpImap\Protocol\Server\Responses\ServerResponse;

interface ClientCommandBus
{
    public function executeCommand(ClientCommand $command): ServerResponse;
}
