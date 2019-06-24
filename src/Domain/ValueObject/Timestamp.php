<?php


namespace Antevenio\DddExample\Domain\ValueObject;

use DateTimeImmutable;
use DateTimeZone;

final class Timestamp implements \JsonSerializable
{
    const TIMEZONE = 'Europe/Madrid';
    /**
     * @var string
     */
    private $timestamp;

    private function __construct(string $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public static function now()
    {
        return self::fromDateTimeImmutable(new DateTimeImmutable(
            'now',
            new DateTimeZone(self::TIMEZONE)
        ));
    }

    public static function fromString($timestamp)
    {
        return new self(
            (new \DateTime(
                $timestamp,
                new DateTimeZone(self::TIMEZONE)
            ))->format(\DateTime::ATOM)
        );
    }

    public static function fromDateTimeImmutable(\DateTimeImmutable $timestamp): Timestamp
    {
        return new self($timestamp->format(\DateTime::ATOM));
    }

    public function asDateTimeImmutable(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat(\DateTime::ATOM, $this->timestamp);
    }

    public function __toString(): string
    {
        return $this->timestamp;
    }

    public function jsonSerialize()
    {
        return $this->timestamp;
    }

    public function greaterThan(Timestamp $other) : bool
    {
        return $this->asDateTimeImmutable() > $other->asDateTimeImmutable();
    }

    public function lessThan(Timestamp $other) : bool
    {
        return $this->asDateTimeImmutable() < $other->asDateTimeImmutable();
    }
}
