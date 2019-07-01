<?php


namespace Antevenio\DddExample\Domain\ValueObject;

use PHPUnit\Framework\TestCase;

class TimestampTest extends TestCase
{

    public function testShouldBeCreatedWithNow()
    {
        $timestamp = Timestamp::now();
        $this->assertNotNull($timestamp);
    }

    public function testShouldBeCreatedWithAnATOMString()
    {
        $timestampString = '2019-05-29T15:16:12+02:00';
        $timestamp = Timestamp::fromString($timestampString);

        $expectedDateTimeImmutable = \DateTimeImmutable::createFromFormat(\DateTime::ATOM, $timestampString);
        $this->assertEquals($expectedDateTimeImmutable, $timestamp->asDateTimeImmutable());
    }

    public function testShouldBeCreatedWithAnTimeStampMysqlString()
    {
        $timestampString = '2019-05-29 15:16:12';
        $timestamp = Timestamp::fromString($timestampString);

        $timestampATOMString = '2019-05-29T15:16:12+02:00';

        $expectedDateTimeImmutable = \DateTimeImmutable::createFromFormat(\DateTime::ATOM, $timestampATOMString);
        $this->assertEquals($expectedDateTimeImmutable, $timestamp->asDateTimeImmutable());
    }

    public function testShouldBeGreatedThanOtherTimestamp()
    {
        $now = Timestamp::now();
        $oneHourAfterNow = Timestamp::fromDateTimeImmutable(
            (new \DateTimeImmutable())->modify('+1 hour')
        );
        $this->assertTrue($oneHourAfterNow->greaterThan($now));
        $this->assertFalse($now->greaterThan($oneHourAfterNow));
    }

    public function testShouldBeLessThanOtherTimestamp()
    {
        $now = Timestamp::now();
        $oneHourBeforeNow = Timestamp::fromDateTimeImmutable(
            (new \DateTimeImmutable())->modify('-1 hour')
        );
        $this->assertTrue($oneHourBeforeNow->lessThan($now));
        $this->assertFalse($now->lessThan($oneHourBeforeNow));
    }
}
