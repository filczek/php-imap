<?php

declare(strict_types=1);

namespace Filczek\PhpImap\Protocol\Client\Commands\SelectedState;

use Filczek\PhpImap\Protocol\Client\Commands\ClientCommand;

/** @see https://datatracker.ietf.org/doc/html/rfc9051#section-6.4.4 */
final readonly class SearchCommand implements ClientCommand
{
    public function __construct(
        public string $search_program,
        public ?string $search_return_opts,
    ) {
    }

    public function __toString(): string
    {
        // TODO implement ValueObjects that validate, quote or not?
        /**
         * > search          = "SEARCH" [search-return-opts] SP search-program
         * >
         * > search-correlator  = SP "(" "TAG" SP tag-string ")"
         * >
         * > search-key      = "ALL" / "ANSWERED" / "BCC" SP astring / "BEFORE" SP date / "BODY" SP astring
         * >                   / "CC" SP astring / "DELETED" / "FLAGGED" / "FROM" SP astring / "KEYWORD" SP flag-keyword
         * >                   / "ON" SP date / "SEEN" / "SINCE" SP date / "SUBJECT" SP astring / "TEXT" SP astring
         * >                   / "TO" SP astring / "UNANSWERED" / "UNDELETED" / "UNFLAGGED" / "UNKEYWORD" SP flag-keyword
         * >                   / "UNSEEN" / "DRAFT" / "HEADER" SP header-fld-name SP astring / "LARGER" SP number64
         * >                   / "NOT" SP search-key / "OR" SP search-key SP search-key / "SENTBEFORE" SP date / "SENTON" SP date
         * >                   / "SENTSINCE" SP date / "SMALLER" SP number64 / "UID" SP sequence-set / "UNDRAFT"
         * >                   / sequence-set / "(" search-key *(SP search-key) ")"
         * >
         * > search-modifier-name = tagged-ext-label
         * >
         * > search-mod-params = tagged-ext-val ; This non-terminal shows recommended syntax for future extensions.
         * >
         * > search-program     = ["CHARSET" SP charset SP] search-key *(SP search-key) ; CHARSET argument to SEARCH MUST be registered with IANA.
         * >
         * > search-ret-data-ext = search-modifier-name SP search-return-value ; Note that not every SEARCH return option is required to have the corresponding ESEARCH return data.
         * >
         * > search-return-data = "MIN" SP nz-number / "MAX" SP nz-number / "ALL" SP sequence-set / "COUNT" SP number / search-ret-data-ext ; All return data items conform to search-ret-data-ext syntax. Note that "$" marker is not allowed after the ALL return data item.
         * >
         * > search-return-opts = SP "RETURN" SP "(" [search-return-opt (SP search-return-opt)] ")"
         * >
         * > search-return-opt  = "MIN" / "MAX" / "ALL" / "COUNT" / "SAVE" / search-ret-opt-ext ; conforms to generic search-ret-opt-ext syntax
         * >
         * > search-ret-opt-ext = search-modifier-name [SP search-mod-params]
         * >
         * > search-return-value = tagged-ext-val ; Data for the returned search option. A single "nz-number"/"number"/"number64" value can be returned as an atom (i.e., without quoting).  A sequence-set can be returned as an atom as well.
         *
         * @see https://datatracker.ietf.org/doc/html/rfc9051#name-formal-syntax
         */

        $arguments = "$this->search_return_opts $this->search_program";
        $arguments = trim($arguments);

        return "SEARCH $arguments";
    }
}
