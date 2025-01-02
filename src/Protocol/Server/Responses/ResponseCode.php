<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Server\Responses;

/**
 * @see https://datatracker.ietf.org/doc/html/rfc9051#section-7.1-3
 * @see https://datatracker.ietf.org/doc/html/rfc9051#section-9 resp-text-code
 */
enum ResponseCode: string
{
    case Alert = "ALERT";
    case AlreadyExists = "ALREADYEXISTS";
    case AppendUid = "APPENDUID";
    case AuthenticationFailed = "AUTHENTICATIONFAILED";
    case AuthorizationFailed = "AUTHORIZATIONFAILED";
    case BadCharset = "BADCHARSET";
    case Cannot = "CANNOT";
    case Capability = "CAPABILITY";
    case ClientBug = "CLIENTBUG";
    case Closed = "CLOSED";
    case ContactAdmin = "CONTACTADMIN";
    case CopyUid = "COPYUID";
    case Corruption = "CORRUPTION";
    case Expired = "EXPIRED";
    case ExpungeIssued = "EXPUNGEISSUED";
    case HasChildren = "HASCHILDREN";
    case InUse = "INUSE";
    case Limit = "LIMIT";
    case NonExistent = "NONEXISTENT";
    case NoPerm = "NOPERM";
    case OverQuota = "OVERQUOTA";
    case Parse = "PARSE";
    case PermanentFlags = "PERMANENTFLAGS";
    case PrivacyRequired = "PRIVACYREQUIRED";
    case ReadOnly = "READ-ONLY";
    case ReadWrite = "READ-WRITE";
    case ServerBug = "SERVERBUG";
    case TryCreate = "TRYCREATE";
    case UidNext = "UIDNEXT";
    case UidNotSticky = "UIDNOTSTICKY";
    case UidValidity = "UIDVALIDITY";
    case Unavailable = "UNAVAILABLE";
    case UnknownContentTransferEncoding = "UNKNOWN-CTE";

    public function equals(self $code): bool
    {
        return $this->value === $code->value;
    }
}
