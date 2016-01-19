<?php

namespace spec\Moccalotto\Maillog;

use PhpSpec\ObjectBehavior;
use Moccalotto\Maillog\LineParser;
use DateTime;

class MaillogFileSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedWith(__DIR__.'/maillog', new LineParser());
        $this->shouldHaveType('Moccalotto\Maillog\MaillogFile');
    }

    public function it_can_parse_a_file()
    {
        $this->beConstructedWith(__DIR__.'/maillog', new LineParser());
        $this->getLinesNewerThan(new DateTime('1999-01-01 00:00:00 UTC'))->shouldHaveCount(3);
        $this->getLinesNewerThan(new DateTime('Nov 29 13:30:21 UTC'))->shouldHaveCount(1);
        $this->getLinesNewerThan(new DateTime('2200-01-01 UTC'))->shouldHaveCount(0);
    }
}
