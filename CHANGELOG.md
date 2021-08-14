# Changelog

All notable changes to `Period` will be documented in this file

## Next - TBD

### Added

- `TimeRangeError` used as the error interface marker.
- `UnableToDrawChart` exception for anything regarding drawing a chart out of `Period` and/or `Sequence` objects.
- `InvalidTimeRange` exception for anything regarding creating an object.
- `Duration::fromSeonds` uses a dedicated fraction parameter and the seconds are no longer expressed using a `float` value.
- `Period::fromNotation` to instantiate a time range object from a mathematical representation and a date format.
- `Period` duration comparison methods accepts also `Duration` and `DateInterval` in addition to `Period` objects.
- `Period::snapTo*` methods family to ease period expansion.

### Fixed

- Switch from using `Closure` object instead of the `callable` pseudo type with the `Sequence` methods.
- `Period::diff` returns a `Sequence` instance instead of an array.
- `Period::__construct` is private.
- `Period` named constructors no longer supports scalar values only objects can be used for instantiation.
- `Period` named constructors, all parameters are required except for the boundaries.
- `Period::timestampInterval` now returns an `int` instead of a `float` value.  
- `Duration` no longer extends a `DateInterval` object.
- `Duration::fromIsoString` supports 3 versions of dealing with fractions with ISO valid string.
- `Datepoint` class renamed `DatePoint`.
- `DatePoint` no longer extends a `DateTimeImmutable` object.
- Argument names are normalized throughout the package. (PHP8 BC break)

### Deprecated

- None

### Removed

- Support for PHP7
- `Period::fromDatepoint` replaced by `Period::fromDate`
- `Period::getStartDate` replaced by `Period::startDate`
- `Period::getEndDate` replaced by `Period::endDate`
- `Period::getDateInterval` replaced by `Period::dateInterval`
- `Period::getTimestampInterval` replaced by `Period::timestampInterval`
- `Period::getBoundaryType` replaced by `Period::boundaries`
- `Period::withBoundaryType` replaced by `Period::withBoundaries`
- `Period::isStartIncluded` replaced by `Period::isStartDateIncluded`
- `Period::isStartExcluded` replaced by `Period::isStartDateExcluded`
- `Period::isEndIncluded` replaced by `Period::isEndDateIncluded`
- `Period::isEndExcluded` replaced by `Period::isEndDateExcluded`
- `Period::getDatePeriod` replaced by `Period::toDatePeriod`
- `Period::getDatePeriodBackwards` replaced by `Period::toDatePeriodBackwards`
- `Period::__string` replaced by `Period::toIso8601`
- `Period::format` replaced by `Period::toNotation`
- `Period::substract` use `Period::subtract` instead
- `Sequence::substract` use `Sequence::subtract` instead
- `Sequence::getIntersections` use `Sequence::intersections` instead
- `Sequence::getGaps` use `Sequence::gaps` instead
- `Sequence::getBoundaries` use `Sequence::length` instead
- `Sequence::getTotalTimestampInterval` use `Sequence::totalTimestampInterval` instead  
- `Duration::__toString` and `Duration::format` with no replacement
- `Duration::create` is removed with no replacement
- `Duration::fromTimeString` is removed with no replacement
- `Datepoint::create` is removed with no replacement
- The `create` prefix is removed from the `Duration` and `Datepoint` named constructors.

Removed all the following namespaced functions from the package: 

- `League\Period\datepoint`
- `League\Period\duration`
- `League\Period\year`
- `League\Period\semester`
- `League\Period\quarter`
- `League\Period\month`
- `League\Period\day`
- `League\Period\hour`
- `League\Period\minute`
- `League\Period\second`
- `League\Period\instant`
- `League\Period\iso_year`
- `League\Period\iso_week`
- `League\Period\interval_after`
- `League\Period\interval_before`
- `League\Period\interval_around`
- `League\Period\interval_from_dateperiod`

## 4.11.0 - 2020-11-11

### Added

- `Period::fromDatepoint`
- `Duration::createFromDateInterval`
- `Duration::createFromTimeString`
- `Duration::createFromChronoString`
- `Duration::createFromSeconds`
- `Duration::create` supports DateInterval spec strings.
- Support for PHP8

### Fixed

- `Duration::create` when using a float will now overflow the results up to the Hour unit.

### Deprecated

- None

### Removed

- None

## 4.10.0 - 2020-03-22

### Added

- `Period::toIso8601`
- Charts featuring ported from [Bakame\Period\Vizualizer](https://github.com/bakame-php/period-visualizer)

### Fixed

- Fix issue with `Sequence::intersections` method.

### Deprecated

- `Period::__string` replaced by `Period::toIso8601`

### Removed

- Support for PHP7.1

## 4.9.0 - 2019-09-02

### Added

- `$boundaryType` argument added to the following named constructors:
    - `Period::fromDay`
    - `Period::fromIsoWeek`
    - `Period::fromMonth`
    - `Period::fromQuarter`
    - `Period::fromSemester`
    - `Period::fromYear`
    - `Period::fromIsoYear`
    
- `Period::subtract`
- `Sequence::subtract`

### Fixed

- None

### Deprecated

- `Period::substract` use `Period::subtract` instead
- `Sequence::substract` use `Sequence::subtract` instead

### Removed

- None

## 4.8.1 - 2019-07-16

### Added

- None

### Fixed

- `Sequence` negative offsets for a object with only one `Period` instance issue [#85](https://github.com/thephpleague/period/issues/85)

### Deprecated

- None

### Removed

- None

## 4.8.0 - 2019-06-20

### Added

- `Datepoint` methods returning `Period` objects supports boundaryType
- `Period::merge` supports empty arguments.
- `Sequence::contains` supports empty arguments.
- `Sequence::unshift` supports empty arguments.
- `Sequence::push` supports empty arguments.
- `Sequence` class supports negative offsets.

### Fixed

- `Duration::adjustedTo` no longer compares `DateInterval` objects to be compatible with PHP7.4+

### Deprecated

- None

### Removed

- None

## 4.7.1 - 2019-05-19

### Added

- None

### Fixed 

- Improve `Duration::createFromDateString` bug fix to take into account [DateInterval::createFromDateString
bug fix](https://bugs.php.net/bug.php?id=50020)
- Update the development tools to Infection 0.13

### Deprecated

- None

### Removed

- None

## 4.7.0 - 2019-03-31

### Added

- `Sequence::getTotalTimestampInterval` see PR [#79](https://github.com/thephpleague/period/issues/79)
- `Period::substract` see PR [#80](https://github.com/thephpleague/period/issues/80)
- `Sequence::substract` see PR [#81](https://github.com/thephpleague/period/issues/80)

### Fixed 

- Update `Duration::createFromDateString` to take into account [DateInterval::createFromDateString
bug fix](https://bugs.php.net/bug.php?id=50020)
- Update the development tools to PHPUnit8.0 and PHPstan 0.11

### Deprecated

- None

### Removed

- None

## 4.6.0 - 2019-03-06

### Added

- `Duration::adjustedTo`
- Internals: added support for PHP7.4 and PHP8.0 in travis.yml

### Fixed

- None

### Deprecated

- `Duration::withoutCarryOver` use `Duration::adjustedTo` instead

### Removed

- None

## 4.5.0 - 2019-02-03

### Added

- `Datepoint::isBefore`
- `Datepoint::bordersOnStart`
- `Datepoint::isStarting`
- `Datepoint::isDuring`
- `Datepoint::isEnding`
- `Datepoint::bordersOnEnd`
- `Datepoint::isAfter`
- `Datepoint::abuts`
- `Duration::create` now supports chronometer format
- `Duration::withoutCarryOver`

### Fixed

- `Period::durationCompare` to take into account Timezone and DST

### Deprecated

- `Duration::__toString` use `Duration::format` instead

### Removed

- None

## 4.4.0 - 2019-01-20

### Added

- Added support for the boundary type
    - `Period::EXCLUDE_START_INCLUDE_END`
    - `Period::INCLUDE_START_EXCLUDE_END`
    - `Period::EXCLUDE_ALL`
    - `Period::INCLUDE_ALL`
    - `Period::getBoundaryType`
    - `Period::isStartExcluded`
    - `Period::isStartIncluded`
    - `Period::isEndExcluded`
    - `Period::isEndIncluded`
    - `Period::withBoundaryType`
    - `Period::__construct` adds the `$boundaryType` argument;
    - `Period::after` adds the `$boundaryType` argument;
    - `Period::before` adds the `$boundaryType` argument;
    - `Period::around` adds the `$boundaryType` argument;
    - `Period::fromDatePeriod` adds the `$boundaryType` argument;
- Added missing [Allen's Algebra intervals](https://www.ics.uci.edu/~alspaugh/cls/shr/allen.html)
    - `Period::bordersOnStart`
    - `Period::bordersOnEnd`
    - `Period::isDuring`
    - `Period::isStartedBy`
    - `Period::isEndedBy`
- Added additional methods to the Sequence class
    - `Sequence::unions`
    - `Sequence::intersections`
    - `Sequence::gaps`
    - `Sequence::boundaries`
    - `Sequence::reduce`

### Fixed

- None

### Deprecated

- `Sequence::getIntersections` use `Sequence::intersections` instead
- `Sequence::getGaps` use `Sequence::gaps` instead
- `Sequence::getBoundaries` use `Sequence::boundaries` instead

### Removed

- None

## 4.3.1 - 2019-01-07

### Added

- None

### Fixed

- `Datepoint::createFromFormat` see issue [#72](https://github.com/thephpleague/period/issues/72)

### Deprecated

- None

### Removed

- None

## 4.3.0 - 2018-12-21

### Added

- `Sequence` implements the `ArrayAccess` interface

### Fixed

- `Sequence::map` must preserve offset index after modification.

### Deprecated

- None

### Removed

- None

## 4.2.0 - 2018-12-19

### Added

- `League\Period\Datepoint`
- `League\Period\Duration`
- `Period::fromIsoYear`
- `Period::fromYear`
- `Period::fromSemester`
- `Period::fromQuarter`
- `Period::fromMonth`
- `Period::fromIsoWeek`
- `Period::fromDay`
- `Period::after`
- `Period::before`
- `Period::around`
- `Period::fromDatePeriod`
- `Sequence::map`

### Fixed

- None

### Deprecated

- `League\Period\datepoint`
- `League\Period\duration`
- `League\Period\year`
- `League\Period\semester`
- `League\Period\quarter`
- `League\Period\month`
- `League\Period\day`
- `League\Period\hour`
- `League\Period\minute`
- `League\Period\second`
- `League\Period\instant`
- `League\Period\iso_year`
- `League\Period\iso_week`
- `League\Period\interval_after`
- `League\Period\interval_before`
- `League\Period\interval_around`
- `League\Period\interval_from_dateperiod`

### Removed

- None

## 4.1.0 - 2018-12-07

### Added

- `League\Period\Sequence`
- `League\Period\InvalidIndex`

### Fixed

- None

### Deprecated

- None

### Removed

- None

## 4.0.1 - 2018-11-30

### Added

- None

### Fixed

- `Period::split` does not work with daylight saving see [issue #68](https://github.com/thephpleague/period/issues/68)

### Deprecated

- None

### Removed

- None

## 4.0.0 - 2018-10-18

### Added

#### Classes

- `Exception` class

#### Methods

- `Period::durationCompare`
- `Period::durationEquals`
- `Period::format`
- `Period::expand`
- `Period::equals`
- `Period::getDatePeriodBackwards`

#### Functions

- `League\Period\datepoint`
- `League\Period\duration`
- `League\Period\year`
- `League\Period\semester`
- `League\Period\quarter`
- `League\Period\month`
- `League\Period\day`
- `League\Period\hour`
- `League\Period\minute`
- `League\Period\second`
- `League\Period\instant`
- `League\Period\iso_year`
- `League\Period\iso_week`
- `League\Period\interval_after`
- `League\Period\interval_before`
- `League\Period\interval_around`
- `League\Period\interval_from_dateperiod`

### Fixed

- The `Period` class is now final
- `Period::JsonSerialize` now returns datepoint in JavaScript compatible notation
- `Period::diff` always returns an array containing two values.
- An instance with a duration equals to `DateInterval('PT0S')` will contains no datepoint not even its starting datepoint.

### Deprecated

- None

### Removed

- Support for PHP `7.0`
- `Period::next`
- `Period::previous`
- `Period::add`
- `Period::sub`
- `Period::createFromYear` replaced by `League\Period\year`
- `Period::createFromMonth` replaced by `League\Period\month`
- `Period::createFromWeek` replaced by `League\Period\iso_week`
- `Period::createFromDay` replaced by `League\Period\day`
- `Period::createFromSemester` replaced by `League\Period\semester`
- `Period::createFromQuarter` replaced by `League\Period\quarter`
- `Period::createFromDuration` replaced by `League\Period\interval_after`
- `Period::createFromDurationBeforeEnd` replaced by `League\Period\interval_before`
- `Period::sameValueAs` replaced by `Period::equals`
- `Period::sameDurationAs` replaced by `Period::durationEquals`
- `Period::compareDuration` replaced by `Period::durationCompare`
- `Period::withDuration` replaced by `Period::withDurationAfterStart`

## 3.4.0 - 2017-11-17

### Added

- `Period::withDurationBeforeEnd`
- `Period::splitBackwards`

### Fixed

- None

### Deprecated

- None

### Removed

- None

## 3.3.0 - 2016-09-19

### Added

- `Period::move`
- `Period::moveStartDate`
- `Period::moveEndDate`

### Fixed

- None

### Deprecated

- `Period::add` you should use `Period::moveEndDate` instead
- `Period::sub` you should use `Period::moveEndDate` instead

### Removed

- None

## 3.2.0 - 2016-05-09

### Added

- `Period::__set_state`
- autoloader to use the library without composer

### Fixed

- `Period::createFromDay` see [issue #36](https://github.com/thephpleague/period/issues/36)
- `Period::intersect` see [issue #33](https://github.com/thephpleague/period/issues/33)

### Deprecated

- None

### Removed

- None

## 3.1.1 - 2016-02-10

### Added

- None

### Fixed

- `Period::contains` see [issue #31](https://github.com/thephpleague/period/issues/31)
- microseconds are taken into account when converting `DateTimeInterface` objects.

### Deprecated

- None

### Removed

- None

## 3.1.0 - 2016-02-01

### Added

- `Period::createFromDay`

### Fixed

- `Period::getDatePeriod` adding the `$option` parameter
- `DateTime` to `DateTimeImmutable` convertion improved for PHP 5.6+ version
- Bumped version requirement to PHP 5.5.9 because of a bug in `DatePeriod` constructor

### Deprecated

- None

### Removed

- None

## 3.0.1 - 2015-12-21

### Added

- None

### Fixed

- `Period::contains` see [issue #28](https://github.com/thephpleague/period/pull/28)

### Deprecated

- None

### Removed

- None

## 3.0.0 - 2015-09-02

### Added

- `Period` implements the `JsonSerializable` interface
- `Period` can now be extended

### Fixed

- `Period` always returns `DateTimeImmutable` objects
- `Period::split` returns a `Generator`
- `Period::merge` no longer throws `RuntimeException`

### Deprecated

- None

### Removed

- support for PHP 5.4
- `Period::getStart`
- `Period::getEnd`
- `Period::getRange`
- `Period::duration`
- `Period::durationDiff`

## 2.5.1 - 2015-03-06

### Fixed

- `Period` always returns `DateTime` objects even when given `DateTimeImmutable` objects.

## 2.5.0 - 2015-03-06

### Added

- `Period::split` to split a `Period` object into smaller `Period` objects
- `Period::getDatePeriod`
- `Period::getDateInterval`
- `Period::getTimestampInterval`
- `Period::getStartDate`
- `Period::getEndDate`
- `Period::dateIntervalDiff`
- `Period::timestampIntervalDiff`

### Fixed
- `DateInterval` validation when created from an integer.

### Deprecated
- `Period::getStart` you should use `Period::getStartDate` instead
- `Period::getEnd` you should use `Period::getEndDate` instead
- `Period::getRange` you should use `Period::getDatePeriod` instead
- `Period::duration` you should use `Period::getDateInterval` or `Period::getTimestampInterval` instead
- `Period::durationDiff` you should use `Period::dateIntervalDiff` or `Period::timestampIntervalDiff` instead

### Remove
- support for PHP 5.3

## 2.4.0 - 2014-12-18

### Added
- Modifying methods:
    - `Period::diff`

### Fixed
- Added support for `DateTimeInterface` interface

## 2.3.0 - 2014-12-15

### Added
- Named constructor:
    - `Period::createFromDurationBeforeEnd`

### Fixed
- `Period::isBefore` `Period::isAfter` bug fixed. must take into account the *half-open* implementation of a `Period`object.

## 2.2.0 - 2014-12-12

### Added
- Modifying methods:
    - `Period::gap`
- Comparing methods:
    - `Period::abuts`
    - `Period::isAfter`
    - `Period::isBefore`

### Fixed
- `Period::overlaps` bug fixed [issue #8](https://github.com/thephpleague/period/issues/8)

## 2.1.0 - 2014-12-08

### Added
- Modifying methods:
    - `Period::next`
    - `Period::previous`
- `Period::__toString` using ISO8601 representation

## 2.0.0 - 2014-10-15

### Added
- Change vendor namespace from `Period` to `League\Period`
- Comparing methods:
    - `Period::sameValueAs`,
    - `Period::compareDuration`,
    - `Period::durationGreaterThan`,
    - `Period::durationLessThan`,
    - `Period::sameDurationAs`,
    - `Period::durationDiff`
- Modifying methods:
    - `Period::add`,
    - `Period::sub`,
    - `Period::intersect`

### Fixed
- `Period::contains` now works with `Period` objects
- `Period::getDuration` accept an optional parameter `$get_as_seconds` if used and set to `true`, the method will return a integer which represents the duration in seconds.
- `Period::merge` now accepts one or more `Period` objects to return the `Period` object which contains all submitted `Period` objects.

## 1.0.1 - 2014-10-08

### Fixed
- The `$interval` parameter can also an integer which represents the interval expressed in seconds.

## 1.0 - 2014-09-24

First stable release of `Period`
