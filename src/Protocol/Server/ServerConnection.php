<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Server;

use Filczek\PhpImap\Protocol\Server\Concerns\HandlesCommand;
use Filczek\PhpImap\Protocol\Server\Concerns\ManagesConnection;

interface ServerConnection extends ManagesConnection, HandlesCommand
{
}
