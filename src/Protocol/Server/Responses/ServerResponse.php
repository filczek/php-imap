<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Server\Responses;

use Stringable;

interface ServerResponse extends Stringable
{
    public function message(): string;

    /** @return string[] */
    public function lines(): array;
}
