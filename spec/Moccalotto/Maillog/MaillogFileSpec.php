<?php

namespace spec\Moccalotto\Maillog;

use PhpSpec\ObjectBehavior;
use Moccalotto\Maillog\LineParser;
use DateTime;

class MaillogFileSpec extends ObjectBehavior
{
    /**
     * @var string
     */
    protected $file = __DIR__ . '/maillog';

    public function it_is_initializable()
    {
        $this->beConstructedWith($this->file, new LineParser());

        $this->shouldHaveType('Moccalotto\Maillog\MaillogFile');
    }

    public function it_can_parse_a_file()
    {
        // HACK: we need to ensure that we know the mtime of the file.
        // Otherwise the tests will only work AFTER nov 29th of the given year.
        touch($this->file, strtotime('Nov 29 13:33:00 UTC'));

        $this->beConstructedWith($this->file, new LineParser());

        $this->getLinesNewerThan(new DateTime('1999-01-01 00:00:00 UTC'))->shouldHaveCount(3);

        $this->getLinesNewerThan(new DateTime('Nov 29 13:30:21 UTC'))->shouldHaveCount(1);

        $this->getLinesNewerThan(new DateTime('2200-01-01 UTC'))->shouldHaveCount(0);
    }
}
