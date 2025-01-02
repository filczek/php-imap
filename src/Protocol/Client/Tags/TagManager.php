<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Client\Tags;

final class TagManager implements ManagesTag
{
    public function __construct(
        private Tag $tag,
    ) {
    }

    public function current(): Tag
    {
        return $this->tag;
    }

    public function advance(): Tag
    {
        $next_tag = $this->current()->next();
        $this->resetTo($next_tag);

        return $next_tag;
    }

    public function resetTo(Tag $tag): void
    {
        $this->tag = $tag;
    }
}
