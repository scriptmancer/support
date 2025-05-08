<?php

namespace Nazim\Support;

class Date
{
    /**
     * Parse a date string into a DateTime object.
     *
     * @param string $date The date string to parse
     * @return \DateTime
     */
    public static function parse($date)
    {
        return new \DateTime($date);
    }

    /**
     * Parse a date from a specified format into a DateTime object.
     *
     * @param string $format The format that the passed date should be in
     * @param string $date The date string to parse
     * @return \DateTime|false Returns DateTime object or false on failure
     */
    public static function parseFromFormat($format, $date)
    {
        return \DateTime::createFromFormat($format, $date);
    }

    /**
     * Get a DateTime object for current date and time.
     *
     * @return \DateTime
     */
    public static function now()
    {
        return new \DateTime();
    }

    /**
     * Get a DateTime object for today at midnight.
     *
     * @return \DateTime
     */
    public static function today()
    {
        return new \DateTime('today');
    }

    /**
     * Get a DateTime object for yesterday at midnight.
     *
     * @return \DateTime
     */
    public static function yesterday()
    {
        return new \DateTime('yesterday');
    }

    /**
     * Get a DateTime object for tomorrow at midnight.
     *
     * @return \DateTime
     */
    public static function tomorrow()
    {
        return new \DateTime('tomorrow');
    }

    /**
     * Add a specified number of days to a date.
     *
     * @param \DateTime $date The date to modify
     * @param int $days The number of days to add
     * @return \DateTime
     */
    public static function addDays($date, $days)
    {
        $clone = clone $date;
        return $clone->modify("+{$days} days");
    }

    /**
     * Subtract a specified number of days from a date.
     *
     * @param \DateTime $date The date to modify
     * @param int $days The number of days to subtract
     * @return \DateTime
     */
    public static function subDays($date, $days)
    {
        $clone = clone $date;
        return $clone->modify("-{$days} days");
    }

    /**
     * Add a specified number of months to a date.
     *
     * @param \DateTime $date The date to modify
     * @param int $months The number of months to add
     * @return \DateTime
     */
    public static function addMonths($date, $months)
    {
        $clone = clone $date;
        return $clone->modify("+{$months} months");
    }

    /**
     * Subtract a specified number of months from a date.
     *
     * @param \DateTime $date The date to modify
     * @param int $months The number of months to subtract
     * @return \DateTime
     */
    public static function subMonths($date, $months)
    {
        $clone = clone $date;
        return $clone->modify("-{$months} months");
    }

    /**
     * Add a specified number of years to a date.
     *
     * @param \DateTime $date The date to modify
     * @param int $years The number of years to add
     * @return \DateTime
     */
    public static function addYears($date, $years)
    {
        $clone = clone $date;
        return $clone->modify("+{$years} years");
    }

    /**
     * Subtract a specified number of years from a date.
     *
     * @param \DateTime $date The date to modify
     * @param int $years The number of years to subtract
     * @return \DateTime
     */
    public static function subYears($date, $years)
    {
        $clone = clone $date;
        return $clone->modify("-{$years} years");
    }

    /**
     * Add a specified number of hours to a date.
     *
     * @param \DateTime $date The date to modify
     * @param int $hours The number of hours to add
     * @return \DateTime
     */
    public static function addHours($date, $hours)
    {
        $clone = clone $date;
        return $clone->modify("+{$hours} hours");
    }

    /**
     * Subtract a specified number of hours from a date.
     *
     * @param \DateTime $date The date to modify
     * @param int $hours The number of hours to subtract
     * @return \DateTime
     */
    public static function subHours($date, $hours)
    {
        $clone = clone $date;
        return $clone->modify("-{$hours} hours");
    }

    /**
     * Add a specified number of minutes to a date.
     *
     * @param \DateTime $date The date to modify
     * @param int $minutes The number of minutes to add
     * @return \DateTime
     */
    public static function addMinutes($date, $minutes)
    {
        $clone = clone $date;
        return $clone->modify("+{$minutes} minutes");
    }

    /**
     * Subtract a specified number of minutes from a date.
     *
     * @param \DateTime $date The date to modify
     * @param int $minutes The number of minutes to subtract
     * @return \DateTime
     */
    public static function subMinutes($date, $minutes)
    {
        $clone = clone $date;
        return $clone->modify("-{$minutes} minutes");
    }

    /**
     * Add a specified number of seconds to a date.
     *
     * @param \DateTime $date The date to modify
     * @param int $seconds The number of seconds to add
     * @return \DateTime
     */
    public static function addSeconds($date, $seconds)
    {
        $clone = clone $date;
        return $clone->modify("+{$seconds} seconds");
    }

    /**
     * Subtract a specified number of seconds from a date.
     *
     * @param \DateTime $date The date to modify
     * @param int $seconds The number of seconds to subtract
     * @return \DateTime
     */
    public static function subSeconds($date, $seconds)
    {
        $clone = clone $date;
        return $clone->modify("-{$seconds} seconds");
    }

    /**
     * Format a date according to the specified format.
     *
     * @param \DateTime $date The date to format
     * @param string $format The format string
     * @return string
     */
    public static function format($date, $format)
    {
        return $date->format($format);
    }

    /**
     * Get the difference between two dates.
     *
     * @param \DateTime $date1 The first date
     * @param \DateTime $date2 The second date
     * @return \DateInterval
     */
    public static function diff($date1, $date2)
    {
        return $date1->diff($date2);
    }

    /**
     * Check if the year of the given date is a leap year.
     *
     * @param \DateTime $date The date to check
     * @return bool
     */
    public static function isLeapYear($date)
    {
        return $date->format('L');
    }

    /**
     * Check if the given date is a weekend day (Saturday or Sunday).
     *
     * @param \DateTime $date The date to check
     * @return bool
     */
    public static function isWeekend($date)
    {
        return $date->format('w') >= 6;
    }

    /**
     * Check if the given date is today.
     *
     * @param \DateTime $date The date to check
     * @return bool
     */
    public static function isToday($date)
    {
        return $date->format('Y-m-d') === self::now()->format('Y-m-d');
    }

    /**
     * Check if the given date is in the future.
     *
     * @param \DateTime $date The date to check
     * @return bool
     */
    public static function isFuture($date)
    {
        return $date->format('Y-m-d') > self::now()->format('Y-m-d');
    }

    /**
     * Check if the given date is in the past.
     *
     * @param \DateTime $date The date to check
     * @return bool
     */
    public static function isPast($date)
    {
        return $date->format('Y-m-d') < self::now()->format('Y-m-d');
    }

    /**
     * Check if the given date is between two other dates.
     *
     * @param \DateTime $date The date to check
     * @param \DateTime $start The start date of the range
     * @param \DateTime $end The end date of the range
     * @return bool
     */
    public static function isBetween($date, $start, $end)
    {
        return $date->format('Y-m-d') >= $start->format('Y-m-d') && $date->format('Y-m-d') <= $end->format('Y-m-d');
    }

    /**
     * Check if two dates are on the same day.
     *
     * @param \DateTime $date1 The first date
     * @param \DateTime $date2 The second date
     * @return bool
     */
    public static function isSameDay($date1, $date2)
    {
        return $date1->format('Y-m-d') === $date2->format('Y-m-d');
    }

    /**
     * Check if two dates are in the same month.
     *
     * @param \DateTime $date1 The first date
     * @param \DateTime $date2 The second date
     * @return bool
     */
    public static function isSameMonth($date1, $date2)
    {
        return $date1->format('Y-m') === $date2->format('Y-m');
    }

    /**
     * Check if two dates are in the same year.
     *
     * @param \DateTime $date1 The first date
     * @param \DateTime $date2 The second date
     * @return bool
     */
    public static function isSameYear($date1, $date2)
    {
        return $date1->format('Y') === $date2->format('Y');
    }
    
    /**
     * Check if two dates have the same time (hours, minutes, seconds).
     *
     * @param \DateTime $date1 The first date
     * @param \DateTime $date2 The second date
     * @return bool
     */
    public static function isSameTime($date1, $date2)
    {
        return $date1->format('H:i:s') === $date2->format('H:i:s');
    }

    /**
     * Check if two dates are in the same week.
     *
     * @param \DateTime $date1 The first date
     * @param \DateTime $date2 The second date
     * @return bool
     */
    public static function isSameWeek($date1, $date2)
    {
        return $date1->format('W') === $date2->format('W');
    }

    /**
     * Format a date to a standard string representation.
     *
     * @param \DateTime $date The date to format
     * @return string
     */
    public static function formatToString($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Check if a date is before another date.
     *
     * @param \DateTime $date1 The date to check
     * @param \DateTime $date2 The date to compare against
     * @return bool
     */
    public static function isBefore($date1, $date2)
    {
        return $date1->format('Y-m-d H:i:s') < $date2->format('Y-m-d H:i:s');
    }

    /**
     * Check if a date is after another date.
     *
     * @param \DateTime $date1 The date to check
     * @param \DateTime $date2 The date to compare against
     * @return bool
     */
    public static function isAfter($date1, $date2)
    {
        return $date1->format('Y-m-d H:i:s') > $date2->format('Y-m-d H:i:s');
    }

    /**
     * Get the start of the day (midnight) for the given date.
     *
     * @param \DateTime $date
     * @return \DateTime
     */
    public static function startOfDay($date)
    {
        $clone = clone $date;
        return $clone->setTime(0, 0, 0);
    }

    /**
     * Get the end of the day (23:59:59) for the given date.
     *
     * @param \DateTime $date
     * @return \DateTime
     */
    public static function endOfDay($date)
    {
        $clone = clone $date;
        return $clone->setTime(23, 59, 59);
    }

    /**
     * Get the start of the month for the given date.
     *
     * @param \DateTime $date
     * @return \DateTime
     */
    public static function startOfMonth($date)
    {
        $clone = clone $date;
        return $clone->modify('first day of this month')->setTime(0, 0, 0);
    }

    /**
     * Get the end of the month for the given date.
     *
     * @param \DateTime $date
     * @return \DateTime
     */
    public static function endOfMonth($date)
    {
        $clone = clone $date;
        return $clone->modify('last day of this month')->setTime(23, 59, 59);
    }

    /**
     * Get the start of the week for the given date.
     *
     * @param \DateTime $date
     * @return \DateTime
     */
    public static function startOfWeek($date)
    {
        $clone = clone $date;
        return $clone->modify('monday this week')->setTime(0, 0, 0);
    }

    /**
     * Get the end of the week for the given date.
     *
     * @param \DateTime $date
     * @return \DateTime
     */
    public static function endOfWeek($date)
    {
        $clone = clone $date;
        return $clone->modify('sunday this week')->setTime(23, 59, 59);
    }

    /**
     * Get the start of the year for the given date.
     *
     * @param \DateTime $date
     * @return \DateTime
     */
    public static function startOfYear($date)
    {
        $clone = clone $date;
        return $clone->modify('first day of january this year')->setTime(0, 0, 0);
    }

    /**
     * Get the end of the year for the given date.
     *
     * @param \DateTime $date
     * @return \DateTime
     */
    public static function endOfYear($date)
    {
        $clone = clone $date;
        return $clone->modify('last day of december this year')->setTime(23, 59, 59);
    }

    /**
     * Get a date for the given number of days ago.
     *
     * @param int $days
     * @return \DateTime
     */
    public static function daysAgo($days)
    {
        return self::subDays(self::now(), $days);
    }

    /**
     * Get a date for the given number of days from now.
     *
     * @param int $days
     * @return \DateTime
     */
    public static function daysFromNow($days)
    {
        return self::addDays(self::now(), $days);
    }

    /**
     * Format the date using a more human-friendly format.
     *
     * @param \DateTime $date
     * @return string
     */
    public static function humanReadable($date)
    {
        return $date->format('F j, Y \a\t g:i a');
    }

    /**
     * Check if the date is a weekday.
     *
     * @param \DateTime $date
     * @return bool
     */
    public static function isWeekday($date)
    {
        return !self::isWeekend($date);
    }

    /**
     * Get the age in years from a date of birth.
     *
     * @param \DateTime $birthDate
     * @return int
     */
    public static function age($birthDate)
    {
        return $birthDate->diff(self::now())->y;
    }
}
