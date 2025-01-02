<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Message;

use ZBateson\MailMimeParser\Header\AddressHeader;
use ZBateson\MailMimeParser\Header\DateHeader;

// TODO refactor till clean
final readonly class MessageDecoder
{
    public function decode(RawMessage $raw_message): Message
    {
        $parsed_message = \ZBateson\MailMimeParser\Message::from((string) $raw_message, false);

        $date = null;
        $from = [];
        $sender = [];
        $reply_to = [];
        $to = [];
        $cc = [];
        $bcc = [];

        $headers = [];
        foreach ($parsed_message->getAllHeaders() as $header) {
            if ($header instanceof DateHeader) {
                if ($header->getName() === 'Date') {
                    $date = $header->getDateTimeImmutable();
                }
            }

            if ($header instanceof AddressHeader) {
                if ($header->getName() == 'From') {
                    $from[] = $header;
                }

                if ($header->getName() == 'Sender') {
                    $sender[] = $header;
                }

                if ($header->getName() == 'Reply-To') {
                    $reply_to[] = $header;
                }

                if ($header->getName() == 'To') {
                    $to[] = $header;
                }

                if ($header->getName() == 'Cc') {
                    $cc[] = $header;
                }

                if ($header->getName() == 'Bcc') {
                    $bcc[] = $header;
                }
            }

            $headers[$header->getName()][] = $header->getValue();
        }
        $from = $this->parseAddresses(...$from);
        $sender = $this->parseAddresses(...$sender);
        $reply_to = $this->parseAddresses(...$reply_to);
        $to = $this->parseAddresses(...$to);
        $cc = $this->parseAddresses(...$cc);
        $bcc = $this->parseAddresses(...$bcc);

        $attachments = [];
        foreach ($parsed_message->getAllAttachmentParts() as $attachment) {
            $content_id = $attachment->getContentId();
            $content_type = $attachment->getContentType();
            $content_disposition = $attachment->getContentDisposition();
            $content_transfer_encoding = $attachment->getContentTransferEncoding();
            $filename = $attachment->getFilename();
            $content = $attachment->getContent();

            $attachments[] = new MessageAttachment(
                id: $content_id,
                type: $content_type,
                disposition: $content_disposition,
                filename: $filename,
                content: $content,
            );
        }

        return new Message(
            headers: $headers,
            date: $date,
            from: $from,
            sender: $sender,
            reply_to: $reply_to,
            to: $to,
            cc: $cc,
            bcc: $bcc,
            subject: $parsed_message->getSubject(),
            text: $parsed_message->getTextContent(),
            html: $parsed_message->getHtmlContent(),
            attachments: $attachments,
        );
    }

    private function parseAddresses(AddressHeader ...$headers): array
    {
        $parsed = [];

        foreach ($headers as $header) {
            foreach ($header->getAddresses() as $address) {
                if (empty(trim($address->getName()))) {
                    $parsed[] = $address->getEmail();
                    continue;
                }

                $parsed[] = "{$address->getName()} <{$address->getEmail()}>";
            }
        }

        return $parsed;
    }
}
