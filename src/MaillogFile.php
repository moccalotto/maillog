<?php

namespace Moccalotto\Maillog;

use DateTime;

class MaillogFile implements Contracts\LineExtractor
{
    /**
     * The parser to use to parse maillog lines
     *
     * @var Contracts\Parser
     */
    protected $parser;

    /**
     * The filename of this log file
     *
     * @var string
     */
    protected $filename;

    /**
     * Constructor
     *
     * @param string $filename          The file to parse.
     * @param Contracts\Parser $parser  The parser to use to parse lines.
     * @param string $linePattern       The regex to use to parse line entries in the log file.
     */
    public function __construct($filename, Contracts\Parser $parser)
    {
        $this->filename = $filename;

        $this->parser = $parser;
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
            $line_obj = $this->parser->stringToMailLogLine($line_str);
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
    protected function fileNewerThan(DateTime $newer_than)
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
        if (!$this->fileNewerThan($newer_than)) {
            return [];
        }

        $results = [];

        foreach (file($this->filename) as $line_str) {
            $line_obj = $this->parser->stringToMailLogLine($line_str);
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
