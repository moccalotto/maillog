<?php

namespace Moccalotto\Maillog\Contracts;

interface Parser
{
    /**
     * Parse a single line from a log file.
     *
     * @param string $string
     *
     * @return MaillogLine|false
     */
    public function stringToMailLogLine($string);
}
