<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Server\Connections;

use Closure;
use Exception;
use Filczek\PhpImap\Protocol\Client\Commands\ClientCommand;
use Filczek\PhpImap\Protocol\Server\Responses\CommandContinuationRequest;
use Filczek\PhpImap\Protocol\Server\Responses\ResponseCondition;
use Filczek\PhpImap\Protocol\Server\Responses\ServerResponse;
use Filczek\PhpImap\Protocol\Server\Responses\TaggedServerResponse;
use Filczek\PhpImap\Protocol\Server\ServerConnection;
use Generator;
use RuntimeException;

final class StreamSocketConnection implements ServerConnection
{
    private mixed $stream = null;
    private string $address;

    public function __construct()
    {
    }

    public function __clone(): void
    {
        if (null === $this->getStream()) {
            return;
        }

        // keep original stream state intact, nullify stream of cloned object
        $this->stream = null;
        $this->reconnect();
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function connectTo(string $address): void
    {
        $this->disconnect();

        $stream = stream_socket_client(
            $address,
            $errno,
            $errstr,
        );

        if (false === $stream) {
            throw new RuntimeException($errstr, $errno);
        }

        $this->stream = $stream;
        $this->address = $address;

        $this->readServerGreetingMessage();
    }

    public function readServerGreetingMessage(): void
    {
        $this->readLinesTill(fn (string $line) => array_find(
            array: ResponseCondition::cases(),
            callback: fn (ResponseCondition $case) => str_contains($line, "* {$case->value}"),
        ));
    }

    public function reconnect(): void
    {
        $this->disconnect();
        $this->connectTo($this->address());
    }

    public function disconnect(): void
    {
        $stream = $this->getStream();
        if (null === $stream) {
            return;
        }

        fclose($stream);
        $this->stream = null;
    }

    public function address(): string
    {
        return $this->address;
    }

    /** @return resource|null */
    private function getStream()
    {
        return $this->stream;
    }

    /** @return array<string, mixed> */
    private function getMetadata(): array
    {
        return stream_get_meta_data($this->getStream());
    }

    private function isBlocked(): bool
    {
        return $this->getMetadata()['blocked'];
    }

    private function changeBlockingMode(bool $blocks): void
    {
        $result = stream_set_blocking($this->getStream(), $blocks);

        if (false === $result) {
            // TODO fill exception
            throw new RuntimeException();
        }
    }

    public function sendData(string $data): void
    {
        $bytes = fwrite($this->getStream(), $data);
        if (false === $bytes) {
            throw new RuntimeException("Unable to write to the stream.");
        }
    }

    /** @return Generator<string> */
    public function readResponse(): Generator
    {
        $blocks = $this->isBlocked();
        $this->changeBlockingMode(true);

        $stream = $this->getStream();
        if (null === $stream) {
            throw new RuntimeException("Unable to read the stream.");
        }

        while ($line = fgets($stream)) {
            yield $line;
        }

        $this->changeBlockingMode($blocks);
    }

    /**
     * @return string[]
     *
     * @see https://datatracker.ietf.org/doc/html/rfc9051#section-2.2.2
     * @see https://datatracker.ietf.org/doc/html/rfc9051#section-7
     */
    private function readLinesTill(Closure $callback): array
    {
        $lines = [];
        foreach ($this->readResponse() as $line) {
            $lines[] = $line;

            if ($callback($line)) {
                break;
            }
        }

        return $lines;
    }

    // TODO refactor
    public function executeCommand(ClientCommand $command): ServerResponse
    {
        $this->sendData((string) $command);

        /**
         * The protocol receiver of an IMAP4rev2 client reads a response line from the server. It then takes action
         * on the response based upon the first token of the response, which can be a tag, a "*", or a "+".
         *
         * @see https://datatracker.ietf.org/doc/html/rfc9051#section-2.2.2-5
         */
        $response_class = null;

        $tag = (string) $command->tag;
        $lines = [];
        foreach ($this->readResponse() as $index => $line) {
            $lines[] = $line;

            if (str_starts_with($line, "$tag ")) {
                break;
            }

            if ($response_class) {
                continue;
            }

            if ($index === 0 && str_starts_with($line, '*')) {
                $response_class = TaggedServerResponse::class;
                continue;
            }

            if ($index === 0 && str_starts_with($line, '+')) {
                $response_class = CommandContinuationRequest::class;
                break;
            }
        }

        if (null === $response_class) {
            throw new RuntimeException("Unknown server response type.");
        }

        $last_line = $lines[array_key_last($lines)];

        if ($response_class === CommandContinuationRequest::class) {
            $message = str_replace(['+'], '', $last_line);
            $message = trim($message);

            return new CommandContinuationRequest(
                message: $message,
                lines: $lines
            );
        }

        $status = array_find(ResponseCondition::cases(), fn (ResponseCondition $case) => str_contains($last_line, $case->value));
        if (null === $status) {
            throw new Exception("Couldn't find response condition on the last line.");
        }

        $message = str_replace([$tag, $status->value], '', $last_line);
        $message = trim($message);

        return new TaggedServerResponse(
            command: $command,
            status: $status,
            message: $message,
            lines: $lines,
        );
    }
}
