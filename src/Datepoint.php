<?php

/**
 * League.Period (https://period.thephpleague.com)
 *
 * (c) Ignace Nyamagana Butera <nyamsprod@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace League\Period;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use function filter_var;
use function intdiv;
use const FILTER_VALIDATE_INT;

/**
 * League Period Datepoint.
 *
 * @package League.period
 * @author  Ignace Nyamagana Butera <nyamsprod@gmail.com>
 * @since   4.2.0
 */
final class Datepoint
{
    /**
     * Returns a position in time expressed as a DateTimeImmutable object.
     *
     * @param DateTimeInterface|string|int $datepoint a position in time
     */
    public static function create(DateTimeInterface|string|int $datepoint): self
    {
        return new self(match (true) {
            $datepoint instanceof DateTimeImmutable => $datepoint,
            $datepoint instanceof DateTimeInterface => DateTimeImmutable::createFromInterface($datepoint),
            false !== ($timestamp = filter_var($datepoint, FILTER_VALIDATE_INT)) => (new DateTimeImmutable())->setTimestamp($datepoint),
            default => new DateTimeImmutable($datepoint),
        });
    }

    private function __construct(private DateTimeImmutable $datepoint)
    {
    }

    public function toDateTimeImmutable(): DateTimeImmutable
    {
        return $this->datepoint;
    }

    /**************************************************
     * interval constructors
     **************************************************/

    /**
     * Returns a Period instance.
     *
     *  - the starting datepoint represents the beginning of the current datepoint second
     *  - the duration is equal to 1 second
     */
    public function getSecond(string $boundaryType = Period::INCLUDE_START_EXCLUDE_END): Period
    {
        $datepoint = $this->datepoint->setTime(
            (int) $this->datepoint->format('H'),
            (int) $this->datepoint->format('i'),
            (int) $this->datepoint->format('s')
        );

        return Period::fromDatepoint($datepoint, $datepoint->add(new DateInterval('PT1S')), $boundaryType);
    }

    /**
     * Returns a Period instance.
     *
     *  - the starting datepoint represents the beginning of the current datepoint minute
     *  - the duration is equal to 1 minute
     */
    public function getMinute(string $boundaryType = Period::INCLUDE_START_EXCLUDE_END): Period
    {
        $datepoint = $this->datepoint->setTime((int) $this->datepoint->format('H'), (int) $this->datepoint->format('i'), 0);

        return Period::fromDatepoint($datepoint, $datepoint->add(new DateInterval('PT1M')), $boundaryType);
    }

    /**
     * Returns a Period instance.
     *
     *  - the starting datepoint represents the beginning of the current datepoint hour
     *  - the duration is equal to 1 hour
     */
    public function getHour(string $boundaryType = Period::INCLUDE_START_EXCLUDE_END): Period
    {
        $datepoint = $this->datepoint->setTime((int) $this->datepoint->format('H'), 0);

        return Period::fromDatepoint($datepoint, $datepoint->add(new DateInterval('PT1H')), $boundaryType);
    }

    /**
     * Returns a Period instance.
     *
     *  - the starting datepoint represents the beginning of the current datepoint day
     *  - the duration is equal to 1 day
     */
    public function getDay(string $boundaryType = Period::INCLUDE_START_EXCLUDE_END): Period
    {
        $datepoint = $this->datepoint->setTime(0, 0);

        return Period::fromDatepoint($datepoint, $datepoint->add(new DateInterval('P1D')), $boundaryType);
    }

    /**
     * Returns a Period instance.
     *
     *  - the starting datepoint represents the beginning of the current datepoint iso week
     *  - the duration is equal to 7 days
     */
    public function getIsoWeek(string $boundaryType = Period::INCLUDE_START_EXCLUDE_END): Period
    {
        $startDate = $this->datepoint
            ->setTime(0, 0)
            ->setISODate(
                (int) $this->datepoint->format('o'),
                (int) $this->datepoint->format('W'),
                1
            );

        return Period::fromDatepoint($startDate, $startDate->add(new DateInterval('P7D')), $boundaryType);
    }

    /**
     * Returns a Period instance.
     *
     *  - the starting datepoint represents the beginning of the current datepoint month
     *  - the duration is equal to 1 month
     */
    public function getMonth(string $boundaryType = Period::INCLUDE_START_EXCLUDE_END): Period
    {
        $startDate = $this->datepoint
            ->setTime(0, 0)
            ->setDate(
                (int) $this->datepoint->format('Y'),
                (int) $this->datepoint->format('n'),
                1
            );

        return Period::fromDatepoint($startDate, $startDate->add(new DateInterval('P1M')), $boundaryType);
    }

    /**
     * Returns a Period instance.
     *
     *  - the starting datepoint represents the beginning of the current datepoint quarter
     *  - the duration is equal to 3 months
     */
    public function getQuarter(string $boundaryType = Period::INCLUDE_START_EXCLUDE_END): Period
    {
        $startDate = $this->datepoint
            ->setTime(0, 0)
            ->setDate(
                (int) $this->datepoint->format('Y'),
                (intdiv((int) $this->datepoint->format('n'), 3) * 3) + 1,
                1
            );

        return Period::fromDatepoint($startDate, $startDate->add(new DateInterval('P3M')), $boundaryType);
    }

    /**
     * Returns a Period instance.
     *
     *  - the starting datepoint represents the beginning of the current datepoint semester
     *  - the duration is equal to 6 months
     */
    public function getSemester(string $boundaryType = Period::INCLUDE_START_EXCLUDE_END): Period
    {
        $startDate = $this->datepoint
            ->setTime(0, 0)
            ->setDate(
                (int) $this->datepoint->format('Y'),
                (intdiv((int) $this->datepoint->format('n'), 6) * 6) + 1,
                1
            );

        return Period::fromDatepoint($startDate, $startDate->add(new DateInterval('P6M')), $boundaryType);
    }

    /**
     * Returns a Period instance.
     *
     *  - the starting datepoint represents the beginning of the current datepoint year
     *  - the duration is equal to 1 year
     */
    public function getYear(string $boundaryType = Period::INCLUDE_START_EXCLUDE_END): Period
    {
        $year = (int) $this->datepoint->format('Y');
        $datepoint = $this->datepoint->setTime(0, 0);

        return Period::fromDatepoint($datepoint->setDate($year, 1, 1), $datepoint->setDate(++$year, 1, 1), $boundaryType);
    }

    /**
     * Returns a Period instance.
     *
     *  - the starting datepoint represents the beginning of the current datepoint iso year
     *  - the duration is equal to 1 iso year
     */
    public function getIsoYear(string $boundaryType = Period::INCLUDE_START_EXCLUDE_END): Period
    {
        $year = (int) $this->datepoint->format('o');
        $datepoint = $this->datepoint->setTime(0, 0);

        return Period::fromDatepoint($datepoint->setISODate($year, 1, 1), $datepoint->setISODate(++$year, 1, 1), $boundaryType);
    }

    /**************************************************
     * relation methods
     **************************************************/

    /**
     * Tells whether the datepoint is before the interval.
     */
    public function isBefore(Period $interval): bool
    {
        return $interval->isAfter($this);
    }

    /**
     * Tell whether the datepoint borders on start the interval.
     */
    public function bordersOnStart(Period $interval): bool
    {
        return $this->datepoint == $interval->getStartDate() && $interval->isStartExcluded();
    }

    /**
     * Tells whether the datepoint starts the interval.
     */
    public function isStarting(Period $interval): bool
    {
        return $interval->isStartedBy($this->datepoint);
    }

    /**
     * Tells whether the datepoint is contained within the interval.
     */
    public function isDuring(Period $interval): bool
    {
        return $interval->contains($this->datepoint);
    }

    /**
     * Tells whether the datepoint ends the interval.
     */
    public function isEnding(Period $interval): bool
    {
        return $interval->isEndedBy($this->datepoint);
    }

    /**
     * Tells whether the datepoint borders on end the interval.
     */
    public function bordersOnEnd(Period $interval): bool
    {
        return $this->datepoint == $interval->getEndDate() && $interval->isEndExcluded();
    }

    /**
     * Tells whether the datepoint abuts the interval.
     */
    public function abuts(Period $interval): bool
    {
        return $this->bordersOnEnd($interval) || $this->bordersOnStart($interval);
    }

    /**
     * Tells whether the datepoint is after the interval.
     */
    public function isAfter(Period $interval): bool
    {
        return $interval->isBefore($this->datepoint);
    }
}
