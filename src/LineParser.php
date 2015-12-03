<?php

namespace Moccalotto\Maillog;

use DateTime;
use DateTimeZone;

class LineParser implements Contracts\Parser
{
    /**
     * @var string
     */
    protected $pattern = <<<'PCRE'
/(?P<logged_at>\w{3}\s{1,2}\d{1,2} \d{2}:\d{2}:\d{2}) .+? to=\<(?P<email>[^>]+)\>.+ status=(?P<status>\w+) \((?P<message>.+?)\)?$/Aui
PCRE;

    /**
     * @var DateTimeZone
     */
    protected $timezone;

    /**
     * Constructor
     *
     * @param string $pattern The pattern to use to parse the line.
     * @param DateTimeZone $timezone The timezone to use if timezone is not stated in the date "field" of the log line.
     */
    public function __construct($pattern = null, DateTimeZone $timezone = null)
    {
        $this->timezone = $timezone ? $timezone : new DateTimeZone('UTC');

        if (null !== $pattern) {
            $this->pattern = $pattern;
        }
    }

    /**
     * Parse a single line from a log file.
     *
     * @param string $line
     *
     * @return MaillogLine|false
     */
    public function stringToMailLogLine($line)
    {
        if (!preg_match($this->pattern, $line, $matches)) {
            return false;
        }

        return new MaillogLine(
            $matches['email'],
            $matches['status'],
            $matches['message'],
            new DateTime($matches['logged_at'], $this->timezone)
        );
    }
}
