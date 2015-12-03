<?php

use Moccalotto\Maillog\MaillogFile;
use Moccalotto\Maillog\LineParser;

require 'vendor/autoload.php';

$file = new MaillogFile('spec/Moccalotto/Maillog/maillog', new LineParser());

print 'All Lines' . PHP_EOL;
print_r($file->getLinesNewerThan(new DateTime('1999-01-01 00:00:00 UTC')));

print 'Only the last line:' . PHP_EOL;
print_r($file->getLinesNewerThan(new DateTime('Nov 29 13:30:21 UTC')));

print 'No lines. File is not even opened' . PHP_EOL;
print_r($file->getLinesNewerThan(new DateTime('2200-01-01 00:00:01 UTC')));
