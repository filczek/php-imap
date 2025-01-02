# PHP IMAP 📬

A fully extensible, IMAP 4rev2-compliant library for PHP, designed with modern development principles and flexibility in
mind.

# 🚨 Working Prototype 🚨

> Note: This library is under active development. The current code is a prototype and may not reflect the final version.

- 💪 **Overengineered**: Designed with flexibility to support custom behaviors and configurations.
- 🚀 **IMAP Extension-Free**: Operates independently of the native
  [IMAP extension](https://www.php.net/manual/en/book.imap.php).
- 📜 **RFC 9051 Compliant**: Strictly adheres to the [IMAP 4rev2](https://datatracker.ietf.org/doc/html/rfc9051)
  specification, ensuring standards-compliant communication with IMAP servers.
- 📚 **Strictly Typed API**: Provides a fully typed and well-documented API for robust and reliable development
  experiences.
- 🕶️ **Future-Proof**: Prepared for modern PHP features and easily adaptable to evolving standards and requirements.

## ️ 🛠️ Getting Started

#### Example Usage

```php
use Filczek\PhpImap\ImapServer;
use Filczek\PhpImap\Protocol\Client\CommandBus\TaggedClientCommandBus;
use Filczek\PhpImap\Protocol\Client\Commands\NotAuthenticatedState\LoginCommand;
use Filczek\PhpImap\Protocol\Client\Tags\AlphanumericTag;
use Filczek\PhpImap\Protocol\Client\Tags\TagManager;
use Filczek\PhpImap\Protocol\Server\Connections\StreamSocketConnection;

$connection = new StreamSocketConnection();
$command_bus = new TaggedClientCommandBus(
    connection: $connection,
    tag: new TagManager(AlphanumericTag::create()),
);

$server = new ImapServer(
    connection: $connection,
    command_bus: $command_bus,
);
$server->connectTo(address: 'ssl://imap.example.com:993'); // Replace with your IMAP server
$response = $server->executeCommand(new LoginCommand(username: 'your-username', password: 'your-password'));

$mailbox = $server->mailbox('INBOX');
foreach ($mailbox->messages() as $message) {
    $message->subject;
    $message->html;
    $message->attachments;
    // ... and more
}
```

## 📝 License

This package is licensed under the [MIT License](LICENSE).

## 🙌 Special Thanks

- [zbateson/mail-mime-parser](https://github.com/zbateson/mail-mime-parser) 
