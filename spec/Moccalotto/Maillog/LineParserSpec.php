<?php

namespace spec\Moccalotto\Maillog;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LineParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Moccalotto\Maillog\LineParser');
    }

    function it_implements_correct_contract()
    {
        $this->shouldImplement('Moccalotto\Maillog\Contracts\Parser');
    }

    function it_parses_a_line()
    {
        $line = <<<maillog
Nov 29 04:07:21 my.host.name postfix/smtp[31573]: D820633602DA: to=<foo.baz@example.com>, relay=blah.com[1.2.3.4]:25, delay=290145, delays=290141/0.01/2.5/1.5, dsn=4.7.0, status=deferred (host blah.example.com[1.2.3.4] You suck. We defer)
maillog;

        $obj = $this->stringToMailLogLine($line);
        $obj->shouldHaveType('Moccalotto\Maillog\MaillogLine');;

    }

    function it_returns_false_when_line_is_malformed()
    {
        $line = <<<maillog
This is a malformed line
maillog;

        $obj = $this->stringToMailLogLine($line);
        $obj->shouldBe(false);
    }

}
