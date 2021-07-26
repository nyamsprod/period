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

use DateInterval;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \League\Period\Period
 */
final class ModificationTest extends TestCase
{
    /** @var string **/
    private $timezone;

    protected function setUp(): void
    {
        $this->timezone = date_default_timezone_get();
    }

    protected function tearDown(): void
    {
        date_default_timezone_set($this->timezone);
    }

    public function testStartingOn(): void
    {
        $expected = new DateTime('2012-03-02');
        $interval = Period::fromDatepoint(new DateTime('2014-01-13'), new DateTime('2014-01-20'));
        $newInterval = $interval->startingOn($expected);
        self::assertSame($newInterval->startDate()->getTimestamp(), $expected->getTimestamp());
        self::assertEquals($interval->startDate(), new DateTimeImmutable('2014-01-13'));
        self::assertSame($interval->startingOn($interval->startDate()), $interval);
    }

    public function testStartingOnFailedWithWrongStartDate(): void
    {
        $this->expectException(InvalidTimeRange::class);
        $interval = Period::fromDatepoint(new DateTime('2014-01-13'), new DateTime('2014-01-20'));
        $interval->startingOn(new DateTime('2015-03-02'));
    }

    public function testEndingOn(): void
    {
        $expected  = new DateTime('2015-03-02');
        $interval = Period::fromDatepoint(new DateTime('2014-01-13'), new DateTime('2014-01-20'));
        $newInterval = $interval->endingOn($expected);
        self::assertSame($newInterval->endDate()->getTimestamp(), $expected->getTimestamp());
        self::assertEquals($interval->endDate(), new DateTimeImmutable('2014-01-20'));
        self::assertSame($interval->endingOn($interval->endDate()), $interval);
    }

    public function testEndingOnFailedWithWrongEndDate(): void
    {
        $this->expectException(InvalidTimeRange::class);
        $interval = Period::fromDatepoint(new DateTime('2014-01-13'), new DateTime('2014-01-20'));
        $interval->endingOn(new DateTime('2012-03-02'));
    }

    public function testExpand(): void
    {
        $interval = (Period::fromDatepoint(new DateTime('2012-02-02'), new DateTime('2012-02-03')))->expand(new DateInterval('P1D'));
        self::assertEquals(new DateTimeImmutable('2012-02-01'), $interval->startDate());
        self::assertEquals(new DateTimeImmutable('2012-02-04'), $interval->endDate());
    }

    public function testExpandRetunsSameInstance(): void
    {
        $interval = Period::fromDatepoint(new DateTime('2012-02-02'), new DateTime('2012-02-03'));
        self::assertSame($interval->expand(new DateInterval('PT0S')), $interval);
    }

    public function testShrink(): void
    {
        $dateInterval = new DateInterval('PT12H');
        $dateInterval->invert = 1;
        $interval = (Period::fromDatepoint(new DateTime('2012-02-02'), new DateTime('2012-02-03')))->expand($dateInterval);
        self::assertEquals(new DateTimeImmutable('2012-02-02 12:00:00'), $interval->startDate());
        self::assertEquals(new DateTimeImmutable('2012-02-02 12:00:00'), $interval->endDate());
    }

    public function testExpandThrowsException(): void
    {
        $this->expectException(InvalidTimeRange::class);
        $dateInterval = new DateInterval('P1D');
        $dateInterval->invert = 1;
        $interval = (Period::fromDatepoint(new DateTime('2012-02-02'), new DateTime('2012-02-03')))->expand($dateInterval);
    }

    public function testMove(): void
    {
        $interval = Period::fromDatepoint(new DateTime('2016-01-01 15:32:12'), new DateTime('2016-01-15 12:00:01'));
        $moved = $interval->move(new DateInterval('P1D'));
        self::assertFalse($interval->equals($moved));
        self::assertTrue($interval->move(new DateInterval('PT0S'))->equals($interval));
    }

    public function testMoveSupportStringIntervals(): void
    {
        $interval = Period::fromDatepoint(new DateTime('2016-01-01 15:32:12'), new DateTime('2016-01-15 12:00:01'));
        $advanced = $interval->move(DateInterval::createFromDateString('1 DAY'));
        $alt = Period::fromDatepoint(new DateTime('2016-01-02 15:32:12'), new DateTime('2016-01-16 12:00:01'));
        self::assertTrue($alt->equals($advanced));
    }

    public function testMoveWithInvertedInterval(): void
    {
        $orig = Period::fromDatepoint(new DateTime('2016-01-01 15:32:12'), new DateTime('2016-01-15 12:00:01'));
        $alt = Period::fromDatepoint(new DateTime('2016-01-02 15:32:12'), new DateTime('2016-01-16 12:00:01'));
        $duration = new DateInterval('P1D');
        $duration->invert = 1;
        self::assertTrue($orig->equals($alt->move($duration)));
    }

    public function testMoveWithInvertedStringInterval(): void
    {
        $orig = Period::fromDatepoint(new DateTime('2016-01-01 15:32:12'), new DateTime('2016-01-15 12:00:01'));
        $alt = Period::fromDatepoint(new DateTime('2016-01-02 15:32:12'), new DateTime('2016-01-16 12:00:01'));
        self::assertTrue($orig->equals($alt->move(DateInterval::createFromDateString('-1 DAY'))));
    }

    public function testWithDurationAfterStart(): void
    {
        $expected = Period::fromDatepoint(new DateTime('2014-03-01'), new DateTimeImmutable('2014-04-01'));
        $period = Period::fromDatepoint(new DateTimeImmutable('2014-03-01'), new DateTime('2014-03-15'));
        self::assertEquals($expected, $period->withDurationAfterStart(DateInterval::createFromDateString('1 MONTH')));
    }

    public function testWithDurationAfterStartThrowsException(): void
    {
        $this->expectException(InvalidTimeRange::class);
        $period = Period::fromDatepoint(new DateTime('2014-03-01'), new DateTime('2014-03-15'));
        $interval = new DateInterval('P1D');
        $interval->invert = 1;
        $period->withDurationAfterStart($interval);
    }

    public function testWithDurationBeforeEnd(): void
    {
        $expected = Period::fromDatepoint(new DateTimeImmutable('2014-02-01'), new DateTime('2014-03-01'));
        $period = Period::fromDatepoint(new DateTimeImmutable('2014-02-15'), new DateTime('2014-03-01'));
        self::assertEquals($expected, $period->withDurationBeforeEnd(DateInterval::createFromDateString('1 MONTH')));
    }

    public function testWithDurationBeforeEndThrowsException(): void
    {
        $this->expectException(InvalidTimeRange::class);
        $period = Period::fromDatepoint(new DateTimeImmutable('2014-02-15'), new DateTimeImmutable('2014-03-01'));
        $interval = new DateInterval('P1D');
        $interval->invert = 1;
        $period->withDurationBeforeEnd($interval);
    }

    public function testMerge(): void
    {
        $period = Period::fromMonth(2014, 3);
        $altPeriod = Period::fromMonth(2014, 4);
        $expected = Period::after(new DateTimeImmutable('2014-03-01'), DateInterval::createFromDateString('2 MONTHS'));
        self::assertEquals($expected, $period->merge($altPeriod));
        self::assertEquals($expected, $altPeriod->merge($period));
        self::assertEquals($expected, $expected->merge($period, $altPeriod));
    }

    public function testMergeThrowsException(): void
    {
        $period = Period::fromMonth(2014, 3);
        self::assertSame($period, $period->merge());
    }

    public function testMoveEndDate(): void
    {
        $orig = Period::after(new DateTimeImmutable('2012-01-01'), DateInterval::createFromDateString('2 MONTH'));
        $period = $orig->moveEndDate(DateInterval::createFromDateString('-1 MONTH'));
        self::assertSame(1, $orig->durationCompare($period));
        self::assertTrue($orig->durationGreaterThan($period));
        self::assertEquals($orig->startDate(), $period->startDate());
    }

    public function testMoveEndDateThrowsException(): void
    {
        $this->expectException(InvalidTimeRange::class);

        Period::after(
            new DateTimeImmutable('2012-01-01'),
            DateInterval::createFromDateString('1 MONTH')
        )->moveEndDate(DateInterval::createFromDateString('-3 MONTHS'));
    }

    public function testMoveStartDateBackward(): void
    {
        $orig = Period::fromMonth(2012, 1);
        $period = $orig->moveStartDate(DateInterval::createFromDateString('-1 MONTH'));
        self::assertSame(-1, $orig->durationCompare($period));
        self::assertTrue($orig->durationLessThan($period));
        self::assertEquals($orig->endDate(), $period->endDate());
        self::assertNotEquals($orig->startDate(), $period->startDate());
    }

    public function testMoveStartDateForward(): void
    {
        $orig = Period::fromMonth(2012, 1);
        $period = $orig->moveStartDate(DateInterval::createFromDateString('2 WEEKS'));
        self::assertSame(1, $orig->durationCompare($period));
        self::assertTrue($orig->durationGreaterThan($period));
        self::assertEquals($orig->endDate(), $period->endDate());
        self::assertNotEquals($orig->startDate(), $period->startDate());
    }

    public function testMoveStartDateThrowsException(): void
    {
        $this->expectException(InvalidTimeRange::class);
        Period::after(new DateTimeImmutable('2012-01-01'), DateInterval::createFromDateString('1 MONTH'))->moveStartDate(DateInterval::createFromDateString('3 MONTHS'));
    }
}
