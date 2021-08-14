---
layout: default
title: The Datepoint class
---

# The DatePoint class

A datepoint is a position in time expressed as a `DateTimeImmutable` object.

The `DatePoint` class is used to ease `DatePoint` manipulation. This class decorates PHP's `DateTimeImmutable` class.
It provides:

- named constructors to easily get a `DateTimeImmutable` object.
- several factory methods to instantiate `Period` classes from a `DateTimeImmutable` object.
- several methods to exposes the relation between a `DateTimeImmutable` object and a `League\Period\Period` instance.

## Named constructors

### DatePoint::fromDate

~~~php
public DatePoint::fromDate(\DateTimeInterface $date): self;
~~~

Returns a `DatePoint` from a `DateTimeInterface` implementing object
 
#### example

~~~php
use League\Period\DatePoint;

$datepoint1 = DatePoint::fromDate(new DateTime('2018-10-15'));
$datepoint2 = DatePoint::fromDate(new DateTimeImmutable('2018-10-15'));

$datepoint1 == $datepoint2; // returns true
~~~

### DatePoint::fromDateString

~~~php
public DatePoint::fromDateString(string $dateString): self;
~~~

Returns a `DatePoint` from a string parsable by the `DateTimeImmutable` constructor.

<p class="message-info">Because we are using PHP's parser, values exceeding ranges will be added to their parent values.</p>
<p class="message-info">If no timezone information is given, the returned <code>DatePoint</code> object will use the current timezone.</p>

#### examples

~~~php
use League\Period\DatePoint;

DatePoint::fromDateString('yesterday'); 
//is equivalent to
DatePoint::fromDate(new DateTimeImmutable('yesterday')); 

DatePoint::fromDateString('2018')
//is equivalent to
DatePoint::fromDate(new DateTimeImmutable('2018')); 
~~~

### DatePoint::fromTimestamp

~~~php
public DatePoint::fromTimestamp(int $timestamp): self;
~~~

Returns a `DatePoint` from an integer interpreted as a timestamp.

<p class="message-info">The timezone will always be <code>UTC</code>.</p>

#### examples

Using the `$datepoint` argument

~~~php
use League\Period\DatePoint;

DatePoint::fromTimestamp(2018); 
~~~
## Accessing the inner Date object

~~~php
public DatePoint::toDate(): DateTimeImmutable;;
~~~

At any given moment you can easily access the underlying `DateTimeImmutable` instance by
calling the `DatePoint::toDate` method.

#### examples

~~~php
use League\Period\DatePoint;

DatePoint::fromTimestamp(2018)->toDate();
//returns an instance equivalent to `new DateTimeImmutable('@2018')`
~~~

## Accessing calendar interval

Once you've got a `DatePoint` instantiated object, you can access a set of calendar type interval using the following method signature.

~~~php
public function method(string $boundaries = Period::INCLUDE_START_EXCLUDE_END): Period;
~~~

where `method` is one of the following date time span:

- `DatePoint::second`
- `DatePoint::minute`
- `DatePoint::hour`
- `DatePoint::day`
- `DatePoint::isoWeek`
- `DatePoint::month`
- `DatePoint::quarter`
- `DatePoint::semester`
- `DatePoint::year`
- `DatePoint::isoYear`

For each a these methods a `Period` object is returned with:

- the `Period::INCLUDE_START_EXCLUDE_END` boundary type by default unless changed using the `$boundaries` argument;
- the starting datepoint represents the beginning of the current datepoint calendar interval;
- the duration associated with the given calendar interval;

#### Examples

~~~php
use League\Period\DatePoint;

$datepoint = Datepoint::fromDateString('2018-06-18 08:35:25');
$hour = $datepoint->hour();
// new Period('2018-06-18 08:00:00', '2018-06-18 09:00:00');
$month = $datepoint->month(Period::INCLUDE_ALL);
echo $month->format('Y-m-d');
// [2018-06-01, 2018-07-01 00:00:00];
$month->contains($datepoint); // true
$hour->contains($datepoint);  // true
$month->contains($hour);      // true
~~~

## Relational method against interval

A datepoint can also be evaluated in relation to a given interval.  
The following methods all share the same signature:
 
~~~php
public function method(Period $interval): bool;
~~~
 
where `method` is one of the basic relation between a datepoint and an interval.

- `DatePoint::isBefore`
- `DatePoint::bordersOnStart`
- `DatePoint::isStarting`
- `DatePoint::isDuring`
- `DatePoint::isEnding`
- `DatePoint::bordersOnEnd`
- `DatePoint::abuts`
- `DatePoint::isAfter`

#### Examples

~~~php
use League\Period\DatePoint;
use League\Period\Period;

$datepoint = DatePoint::fromDateString('2018-01-18 10:00:00');
$datepoint->isBorderingOnStart(
    Period::after($datepoint, new DateInterval('PT3M'), Period::EXCLUDE_START_INCLUDE_END)
); //  true


$datepoint->isBorderingOnStart(
    Period::after($datepoint, new DateInterval('PT3M'), Period::INCLUDE_ALL)
); // false

$datepoint->isAfter(
    Period::before(
        DatePoint::fromDateString('2018-01-13 23:34:28'), 
        DateInterval::createFromDateString('3 minutes'), 
        Period::INCLUDE_START_EXCLUDE_END
    )
);  // true
~~~
