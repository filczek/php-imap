<?php

declare(strict_types=1);

namespace Filczek\PhpImap;

use Filczek\PhpImap\Message\Message;
use Filczek\PhpImap\Message\MessageDecoder;
use Filczek\PhpImap\Message\RawMessage;
use Filczek\PhpImap\Protocol\Client\Commands\AuthenticatedState\SelectCommand;
use Filczek\PhpImap\Protocol\Client\Commands\ClientCommand;
use Filczek\PhpImap\Protocol\Client\Commands\SelectedState\FetchCommand;
use Filczek\PhpImap\Protocol\Server\Responses\ResponseCondition;
use Filczek\PhpImap\Protocol\Server\Responses\ServerResponse;
use Filczek\PhpImap\Protocol\Server\Responses\TaggedServerResponse;
use Generator;
use RuntimeException;

// TODO this is Proof-of-Concept stage, refactor
final readonly class Mailbox
{
    public function __construct(
        private string $mailbox,
        private ImapServer $server,
    ) {
    }

    public function mailbox(): string
    {
        return $this->mailbox;
    }

    public function executeCommand(ClientCommand $command): ServerResponse
    {
        return $this->server->executeCommand($command);
    }

    /** @return Generator<Message> */
    public function messages(): Generator
    {
        $response = $this->executeCommand(new SelectCommand($this->mailbox()));

        $last_uid = null;
        foreach ($response->lines() as $line) {
            if (str_contains($line, 'EXISTS')) {
                $last_uid = substr($line, strpos($line, '*') + 1, strrpos($line, 'EXISTS') - 1);
                $last_uid = trim($last_uid);
                break;
            }
        }

        $current_uid = (int) $last_uid;

        while ($current_uid > 0) {
            $response = $this->executeCommand(new FetchCommand("$current_uid", "BODY.PEEK[]"));
            if (false === $response instanceof TaggedServerResponse) {
                throw new RuntimeException($response->message());
            }

            if ($response->status->notMatches(ResponseCondition::Ok)) {
                throw new RuntimeException($response->message());
            }

            // TODO write message extractor from the provided lines
            $mime_message = $response->lines();
            // remove '* 462 FETCH (BODY[] {...}\r\n' line
            array_shift($mime_message);
            // remove 'A0004 OK Success \r\n' line
            array_pop($mime_message);
            // remove ')\r\n'
            array_pop($mime_message);

            $raw_message = RawMessage::fromLines($mime_message);
            $message = (new MessageDecoder())->decode($raw_message);

            yield $message;

            break;

            $current_uid -= 1;
        }
    }
}
