<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Server\Concerns;

use Filczek\PhpImap\Protocol\Client\Commands\ClientCommand;
use Filczek\PhpImap\Protocol\Server\Responses\ServerResponse;

interface HandlesCommand
{
    /**
     * Executes a client command and handles the response from the IMAP server.
     *
     * @param ClientCommand $command The command to execute.
     * @return ServerResponse The response.
     */
    public function executeCommand(ClientCommand $command): ServerResponse;
}
