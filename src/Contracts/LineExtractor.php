<?php

namespace Moccalotto\Maillog\Contracts;

use DateTime;

interface LineExtractor
{
    /**
     * Extract all MaillogLine objects from the file.
     *
     * @return MaillogLine[]
     */
    public function lines();

    /**
     * Get all the MaillogLine objects that are newer than $newer_than.
     *
     * @param DateTime $newer_than
     *
     * @return MaillogLine[] array of lines, parsed into arrays
     */
    public function getLinesNewerThan(DateTime $newer_than);
}
