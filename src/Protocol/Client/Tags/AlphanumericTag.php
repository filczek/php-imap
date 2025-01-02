<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Client\Tags;

// TODO Test
// TODO Validate according to the RFC, should be alphanumeric
/**
 * Implementation of the alphanumeric tag shown in the RFC.
 *
 * @example A0001, A0002, etc.
 * @see https://datatracker.ietf.org/doc/html/rfc9051#section-2.2.1-1
 */
final readonly class AlphanumericTag implements Tag
{
    public static function create(?string $prefix = "A", ?int $number = 1): self
    {
        return new self($prefix, $number);
    }

    private function __construct(
        private ?string $prefix,
        private ?int $value
    ) {
    }

    /** @inheritDoc */
    public function next(): static
    {
        return self::create($this->prefix, $this->value + 1);
    }

    public function __toString()
    {
        $value = str_pad((string) $this->value, 4, '0', STR_PAD_LEFT);
        return "{$this->prefix}$value";
    }
}
