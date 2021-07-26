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
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class DurationTest extends TestCase
{
    private string $timezone;

    protected function setUp(): void
    {
        $this->timezone = date_default_timezone_get();
    }

    protected function tearDown(): void
    {
        date_default_timezone_set($this->timezone);
    }

    private function formatDuration(Duration $duration): string
    {
        $interval = $duration->toInterval();

        $date = 'P';
        foreach (['Y' => 'y', 'M' => 'm', 'D' => 'd'] as $key => $value) {
            if (0 !== $interval->$value) {
                $date .= '%'.$value.$key;
            }
        }

        $time = 'T';
        foreach (['H' => 'h', 'M' => 'i'] as $key => $value) {
            if (0 !== $interval->$value) {
                $time .= '%'.$value.$key;
            }
        }

        if (0.0 !== $interval->f) {
            $second = $interval->s + $interval->f;
            if (0 > $interval->s) {
                $second = $interval->s - $interval->f;
            }

            $second = rtrim(sprintf('%f', $second), '0');

            return $interval->format($date.$time).$second.'S';
        }

        if (0 !== $interval->s) {
            return $interval->format($date.$time.'%sS');
        }

        if ('T' !== $time) {
            return $interval->format($date.$time);
        }

        if ('P' !== $date) {
            return $interval->format($date);
        }

        return 'PT0S';
    }

    public function testCreateFromDateString(): void
    {
        $duration = Duration::fromDateString('+1 DAY');

        self::assertSame(1, $duration->toInterval()->d);
        self::assertFalse($duration->toInterval()->days);
    }

    /**
     * @return iterable<string, array<string>>
     */
    public function getDurationCreateFailsProvider(): iterable
    {
        return [
            'invalid interval spec 1' => ['PT'],
            'invalid interval spec 2' => ['P'],
            'invalid interval spec 3' => ['PT1'],
            'invalid interval spec 4' => ['P3'],
            'invalid interval spec 5' => ['PT3X'],
            'invalid interval spec 6' => ['PT3s'],
            'invalid string' => ['blablabbla'],
        ];
    }

    /**
     * @dataProvider getDurationCreateFromDateStringFailsProvider
     */
    public function testDurationCreateFromDateStringFails(string $input): void
    {
        $this->expectWarning();

        Duration::fromDateString($input);
    }

    /**
     * @return iterable<string,array<string>>
     */
    public function getDurationCreateFromDateStringFailsProvider(): iterable
    {
        return [
            'invalid interval spec 1' => ['yolo'],
        ];
    }

    /**
     * @dataProvider getDurationFromSecondsSuccessfulProvider
     */
    public function testCreateFromSeconds(int $seconds, int $fraction, string $expected): void
    {
        self::assertSame($expected, $this->formatDuration(Duration::fromSeconds($seconds, $fraction)));
    }

    /**
     * @return array<string, array{seconds:int, fraction:int, expected:string}>
     */
    public function getDurationFromSecondsSuccessfulProvider(): array
    {
        return [
            'from an integer' => [
                'seconds' => 0,
                'fraction' => 0,
                'expected' => 'PT0S',
            ],
            'negative seconds' => [
                'seconds' => -3,
                'fraction' => 2345,
                'expected' => 'PT-3.002345S',
            ],
        ];
    }

    public function testItFailsToCreateADurationWithANegativeFraction(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Duration::fromSeconds(32, -1);
    }

    /**
     * @dataProvider providesValidIsoString
     *
     */
    public function testIntervalWithFraction(string $input, string $expected): void
    {
        self::assertSame($expected, $this->formatDuration(Duration::fromIsoString($input)));
    }

    /**
     * @return iterable<string, array{input:string, expected:string}>
     */
    public function providesValidIsoString(): iterable
    {
        return [
            'IsoString with fraction v1' => [
                'input' => 'PT3.1S',
                'expected' => 'PT3.1S',
            ],
            'IsoString with fraction v2' => [
                'input' => 'P0000-00-00T00:05:00.023658',
                'expected' => 'PT5M0.023658S',
            ],
            'IsoString with fraction v3' => [
                'input' => 'PT5M23658F',
                'expected' => 'PT5M0.023658S',
            ],
        ];
    }


    /**
     * @dataProvider fromChronoFailsProvider
     */
    public function testCreateFromChronoStringFails(string $input): void
    {
        $this->expectException(InvalidTimeRange::class);

        Duration::fromChronoString($input);
    }

    /**
     * @return iterable<string, array<string>>
     */
    public function fromChronoFailsProvider(): iterable
    {
        return [
            'invalid string' => ['foobar'],
            'float like string' => ['-28.5'],
        ];
    }

    /**
     * @dataProvider fromChronoProvider
     */
    public function testCreateFromChronoStringSucceeds(string $chronometer, string $expected): void
    {
        $duration = Duration::fromChronoString($chronometer);

        self::assertSame($expected, $this->formatDuration($duration));
    }

    /**
     * @return iterable<string, array{chronometer:string, expected:string}>
     */
    public function fromChronoProvider(): iterable
    {
        return [
            'minute and seconds' => [
                'chronometer' => '1:2',
                'expected' => 'PT1M2S',
            ],
            'hour, minute, seconds' => [
                'chronometer' => '1:2:3',
                'expected' => 'PT1H2M3S',
            ],
            'handling 0 prefix' => [
                'chronometer' => '00001:00002:000003.0004',
                'expected' => 'PT1H2M3.0004S',
            ],
            'negative chrono' => [
                'chronometer' => '-12:28.5',
                'expected' => 'PT12M28.5S',
            ],
        ];
    }

    /**
     * @dataProvider adjustedToDataProvider
     * @param int|string|DateTimeInterface $reference_date
     */
    public function testAdjustedTo(string $input, int|string|DateTimeInterface $reference_date, string $expected): void
    {
        $duration = Duration::fromIsoString($input);
        /** @var DateTimeInterface $date */
        $date = match (true) {
            is_int($reference_date) => DatePoint::fromTimestamp($reference_date)->toDate(),
            is_string($reference_date) => DatePoint::fromDateString($reference_date)->toDate(),
            default  => $reference_date,
        };

        self::assertSame($expected, $this->formatDuration($duration->adjustedTo($date)));
    }

    /**
     * @return iterable<string, array{input:string, reference_date:int|string|DateTimeInterface, expected:string}>
     */
    public function adjustedToDataProvider(): iterable
    {
        return [
            'nothing to carry over' => [
                'input' => 'PT3H',
                'reference_date' => 0,
                'expected' => 'PT3H',
            ],
            'hour transformed in days' => [
                'input' => 'PT24H',
                'reference_date' => 0,
                'expected' => 'P1D',
            ],
            'days transformed in months' => [
                'input' => 'P31D',
                'reference_date' => 0,
                'expected' => 'P1M',
            ],
            'months transformed in years' => [
                'input' => 'P12M',
                'reference_date' => 0,
                'expected' => 'P1Y',
            ],
            'leap year' => [
                'input' => 'P29D',
                'reference_date' => '2020-02-01',
                'expected' => 'P1M',
            ],
            'none leap year' => [
                'input' => 'P29D',
                'reference_date' => '2019-02-01',
                'expected' => 'P1M1D',
            ], /* THIS IS FIXED AS OF PHP8.1
            'dst day' => [
                'input' => 'PT4H',
                'reference_date' => new DateTime('2019-03-31', new DateTimeZone('Europe/Brussels')),
                'expected' => 'PT3H',
            ],*/
            'non dst day' => [
                'input' => 'PT4H',
                'reference_date' => new DateTime('2019-04-01', new DateTimeZone('Europe/Brussels')),
                'expected' => 'PT4H',
            ],
        ];
    }
}
