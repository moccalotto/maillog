<?php

namespace Moccalotto\Maillog;

use DateTime;
use JsonSerializable;

class MaillogLine implements JsonSerializable
{
    public function __construct($email, $status, $message, DateTime $loggedAt)
    {
        $this->email = $email;
        $this->status = strtolower($status);
        $this->message = $message;
        $this->loggedAt = $loggedAt;
    }

    public function uid()
    {
        return md5($this->email.$this->loggedAt->format('U'));
    }

    public function email()
    {
        return $this->email;
    }

    public function status()
    {
        return $this->status;
    }

    public function code()
    {
        if (preg_match('/\\d{3}/A', $this->message, $matches)) {
            return (int) $matches[0];
        }

        if (preg_match('/ said: (\\d{3})/', $this->message, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    public function message()
    {
        return $this->message;
    }

    public function loggedAt()
    {
        return $this->loggedAt;
    }

    public function jsonSerialize()
    {
        return [
            'uid' => $this->uid(),
            'email' => $this->email,
            'status' => $this->status,
            'message' => $this->message,
            'code' => $this->code(),
            'loggedAt' => $this->loggedAt->format('c'),
        ];
    }
}
