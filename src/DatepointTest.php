<?php

/**
 * League.Period (https://period.thephpleague.com)
 *
 * (c) Ignace Nyamagana Butera <nyamsprod@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Period;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

final class DatepointTest extends TestCase
{
    /** @var string **/
    private $timezone;

    public function setUp(): void
    {
        $this->timezone = date_default_timezone_get();
    }

    public function tearDown(): void
    {
        date_default_timezone_set($this->timezone);
    }

    /**
     * @dataProvider isAfterProvider
     */
    public function testIsAfter(Period $interval, DateTimeInterface $input, bool $expected): void
    {
        self::assertSame($expected, Datepoint::fromDateTimeInterface($input)->isAfter($interval));
    }

    public function isAfterProvider(): array
    {
        return [
            'range exclude end date success' => [
                'interval' => Period::fromMonth(2012, 1),
                'input' => new DateTime('2015-01-01'),
                'expected' => true,
            ],
            'range exclude end date fails' => [
                'interval' => Period::fromMonth(2012, 1),
                'input' => new DateTime('2010-01-01'),
                'expected' => false,
            ],
            'range exclude end date abuts date fails' => [
                'interval' => Period::fromMonth(2012, 1),
                'input' => new DateTime('2012-01-01'),
                'expected' => false,
            ],
            'range exclude start date success' => [
                'interval' => Period::after('2012-01-01', '1 MONTH', Period::EXCLUDE_START_INCLUDE_END),
                'input' => new DateTime('2015-01-01'),
                'expected' => true,
            ],
            'range exclude start date fails' => [
                'interval' => Period::after('2012-01-01', '1 MONTH', Period::EXCLUDE_START_INCLUDE_END),
                'input' => new DateTime('2010-01-01'),
                'expected' => false,
            ],
            'range exclude start date abuts date success' => [
                'interval' => Period::after('2012-01-01', '1 MONTH', Period::EXCLUDE_START_INCLUDE_END),
                'input' => new DateTime('2012-02-01'),
                'expected' => false,
            ],
        ];
    }

    /**
     * @dataProvider isBeforeProvider
     */
    public function testIsBefore(Period $interval, DateTimeInterface $input, bool $expected): void
    {
        self::assertSame($expected, Datepoint::fromDateTimeInterface($input)->isBefore($interval));
    }

    public function isBeforeProvider(): array
    {
        return [
            'range exclude end date success' => [
                'interval' => Period::fromMonth(2012, 1),
                'input' => new DateTime('2010-01-01'),
                'expected' => true,
            ],
            'range exclude end date fails' => [
                'interval' => Period::fromMonth(2012, 1),
                'input' => new DateTime('2015-01-01'),
                'expected' => false,
            ],
            'range exclude end date abuts date fails' => [
                'interval' => Period::after('2012-01-01', '1 MONTH', Period::EXCLUDE_START_INCLUDE_END),
                'input' => new DateTime('2012-02-01'),
                'expected' => false,
            ],
            'range exclude start date success' => [
                'interval' => Period::after('2012-01-01', '1 MONTH', Period::EXCLUDE_START_INCLUDE_END),
                'input' => new DateTime('2012-01-01'),
                'expected' => true,
            ],
        ];
    }

    public function testDatepointBorderingOn(): void
    {
        $datepoint = Datepoint::fromString('2018-01-18 10:00:00');

        $intervalBorderOnStartTrue = Period::after($datepoint, '3 minutes', Period::EXCLUDE_START_INCLUDE_END);
        self::assertTrue($datepoint->bordersOnStart($intervalBorderOnStartTrue));
        self::assertTrue($datepoint->abuts($intervalBorderOnStartTrue));

        $intervalBorderOnStartFalse = Period::after($datepoint, '3 minutes', Period::INCLUDE_ALL);
        self::assertFalse($datepoint->bordersOnStart($intervalBorderOnStartFalse));
        self::assertFalse($datepoint->abuts($intervalBorderOnStartFalse));

        $intervalBorderOnEndTrue = Period::before($datepoint, '3 minutes', Period::INCLUDE_START_EXCLUDE_END);
        self::assertTrue($datepoint->bordersOnEnd($intervalBorderOnEndTrue));
        self::assertTrue($datepoint->abuts($intervalBorderOnEndTrue));

        $intervalBorderOnEndFalse = Period::before($datepoint, '3 minutes', Period::EXCLUDE_START_INCLUDE_END);
        self::assertFalse($datepoint->bordersOnEnd($intervalBorderOnEndFalse));
        self::assertFalse($datepoint->abuts($intervalBorderOnEndFalse));
    }

    /**
     * @dataProvider isDuringDataProvider
     *
     * @param DateTimeInterface|string $input
     */
    public function testIsDuring(Period $interval, $input, bool $expected): void
    {
        $datepoint = $input instanceof DateTimeInterface ? Datepoint::fromDateTimeInterface($input) : Datepoint::fromString($input);

        self::assertSame($expected, $datepoint->isDuring($interval));
    }

    public function isDuringDataProvider(): array
    {
        return [
            'contains returns true with a DateTimeInterface object' => [
                Period::fromDatepoint(new DateTimeImmutable('2014-03-10'), new DateTimeImmutable('2014-03-15')),
                new DateTime('2014-03-12'),
                true,
            ],
            'contains returns false with a DateTimeInterface object' => [
                Period::fromDatepoint(new DateTimeImmutable('2014-03-13'), new DateTimeImmutable('2014-03-15')),
                new DateTime('2015-03-12'),
                false,
            ],
            'contains returns false with a DateTimeInterface object after the interval' => [
                Period::fromDatepoint(new DateTimeImmutable('2014-03-13'), new DateTimeImmutable('2014-03-15')),
                '2012-03-12',
                false,
            ],
            'contains returns false with a DateTimeInterface object before the interval' => [
                Period::fromDatepoint(new DateTimeImmutable('2014-03-13'), new DateTimeImmutable('2014-03-15')),
                '2014-04-01',
                false,
            ],
            'contains returns false with O duration Period object' => [
                Period::fromDatepoint(new DateTimeImmutable('2012-03-12'), new DateTimeImmutable('2012-03-12')),
                new DateTime('2012-03-12'),
                false,
            ],
            'contains datetime edge case datetime equals start date' => [
                Period::after('2012-01-08', '1 DAY'),
                new DateTime('2012-01-08'),
                true,
            ],
            'contains datetime edge case datetime equals end date' => [
                Period::after('2012-01-08', '1 DAY'),
                new DateTime('2012-01-09'),
                false,
            ],
            'contains datetime edge case datetime equals start date OLCR interval' => [
                Period::after('2012-01-08', '1 DAY', Period::EXCLUDE_START_INCLUDE_END),
                new DateTime('2012-01-08'),
                false,
            ],
            'contains datetime edge case datetime equals end date CLCR interval' => [
                Period::after('2012-01-08', '1 DAY', Period::EXCLUDE_ALL),
                new DateTime('2012-01-09'),
                false,
            ],
        ];
    }

    /**
     * @dataProvider startsDataProvider
     */
    public function testStarts(Period $interval, DateTimeInterface $index, bool $expected): void
    {
        self::assertSame($expected, Datepoint::fromDateTimeInterface($index)->isStarting($interval));
    }

    public function startsDataProvider(): array
    {
        $datepoint = new DateTime('2012-01-01');

        return [
            [
                Period::fromDatepoint($datepoint, new DateTime('2012-01-15'), Period::EXCLUDE_ALL),
                $datepoint,
                false,
            ],
            [
                Period::fromDatepoint($datepoint, new DateTime('2012-01-15'), Period::INCLUDE_START_EXCLUDE_END),
                $datepoint,
                true,
            ],
        ];
    }

    /**
     * @dataProvider isEndingDataProvider
     */
    public function testFinishes(Period $interval, DateTimeInterface $index, bool $expected): void
    {
        self::assertSame($expected, Datepoint::fromDateTimeInterface($index)->isEnding($interval));
    }

    public function isEndingDataProvider(): array
    {
        $datepoint = new DateTime('2012-01-16');

        return [
            [
                Period::fromDatepoint(new DateTime('2012-01-01'), $datepoint, Period::EXCLUDE_ALL),
                $datepoint,
                false,
            ],
            [
                Period::fromDatepoint(new DateTime('2012-01-01'), $datepoint, Period::INCLUDE_ALL),
                $datepoint,
                true,
            ],
        ];
    }
}
