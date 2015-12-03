<?php

namespace Moccalotto\Maillog;

use DateTime;
use DateTimeZone;

class MaillogFile
{
    /**
     * @var string
     */
    protected $linePattern = <<<'PCRE'
/(?P<logged_at>\w{3}\s{1,2}\d{1,2} \d{2}:\d{2}:\d{2}) .+? to=\<(?P<email>[^>]+)\>.+ status=(?P<status>\w+) \((?P<message>.+?)\)?$/Aui
PCRE;

    /**
     * The filename of this log file
     *
     * @var string
     */
    protected $filename;

    /**
     * @var DateTimeZone
     */
    protected $timezone;

    /**
     * Constructor
     *
     * @param string $filename          The file to parse
     * @param DateTimeZone $timezone    The timezone to use to parse dates from the log file.
     * @param string $linePattern       The regex to use to parse line entries in the log file.
     */
    public function __construct($filename, DateTimeZone $timezone = null, $linePattern = null)
    {
        $this->filename = $filename;

        $this->timezone = $timezone ? $timezone : new DateTimeZone('UTC');

        if (null !== $linePattern) {
            $this->linePattern = $linePattern;
        }
    }

    /**
     * Parse a single line from a log file.
     *
     * @param string $line
     *
     * @return MaillogLine|false
     */
    protected function stringToMailLogLine($line)
    {
        if (!preg_match($this->linePattern, $line, $matches)) {
            return false;
        }

        return new MaillogLine(
            $matches['email'],
            $matches['status'],
            $matches['message'],
            new DateTime($matches['logged_at'], $this->timezone)
        );
    }

    /**
     * Extract all MaillogLine objects from the file
     *
     * @return MaillogLine[]
     */
    public function lines()
    {
        $results = [];

        foreach (file($this->filename) as $line_str) {
            $line_obj = $this->stringToMailLogLine($line_str);
            if (!$line_obj) {
                // The line is not relevant, continue
                continue;
            }

            $results[] = $line_obj;
        }

        return $results;
    }

    /**
     * Is the file newer than a given date.
     *
     * @param DateTime $newer_than
     *
     * @return bool
     */
    public function newerThan(DateTime $newer_than)
    {
        return filemtime($this->filename) > $newer_than->format('U');
    }

    /**
     * Get all the MaillogLine objects that are newer than $newer_than
     *
     * @param DateTime $newer_than
     *
     * @return MaillogLine[] array of lines, parsed into arrays
     */
    public function getLinesNewerThan(DateTime $newer_than)
    {
        if (!$this->newerThan($newer_than)) {
            return [];
        }

        $results = [];

        foreach (file($this->filename) as $line_str) {
            $line_obj = $this->stringToMailLogLine($line_str);
            if (!$line_obj) {
                // The line is not relevant, continue
                continue;
            }

            if ($line_obj->loggedAt() <= $newer_than) {
                // the line is outdated, continue
                continue;
            }

            $results[] = $line_obj;
        }

        return $results;
    }
}
