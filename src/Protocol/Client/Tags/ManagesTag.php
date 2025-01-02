<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Client\Tags;

interface ManagesTag
{
    public function current(): Tag;

    public function advance(): Tag;

    public function resetTo(Tag $tag): void;
}
