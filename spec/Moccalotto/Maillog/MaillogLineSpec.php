<?php

namespace spec\Moccalotto\Maillog;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MaillogLineSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(
            'test@example.com',
            'sent',
            '250 this is a message',
            new \DateTime('now', new \DateTimeZone('UTC'))
        );
        $this->shouldHaveType('Moccalotto\Maillog\MaillogLine');
    }

    function it_has_all_necessary_dto_functionality()
    {
        $dt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->beConstructedWith(
            'test@example.com',
            'sent',
            '250 this is a message',
            $dt
        );
        $this->email()->shouldBe('test@example.com');
        $this->status()->shouldBe('sent');
        $this->code()->shouldBe(250);
        $this->message()->shouldBe('250 this is a message');
        $this->loggedAt()->shouldBe($dt);
    }

    function it_can_extract_code_from_alternate_message_format()
    {
        $dt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->beConstructedWith(
            'test@example.com',
            'sent',
            'host foo.bar.com[::1] said: 550-5.7.1 You suck',
            $dt
        );
        $this->email()->shouldBe('test@example.com');
        $this->status()->shouldBe('sent');
        $this->code()->shouldBe(550);
    }
}
