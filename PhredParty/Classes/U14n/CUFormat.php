<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * A group of static methods that can format numbers, percentages, amounts of currency, as well as dates and time using
 * the local formatting rules associated with a locale and, for some methods, the language of a locale.
 *
 * **You can refer to this class by its alias, which is** `UFmt`.
 */

// Method signatures:
//   static CUStringObject number ($number, CULocale $inLocale = null)
//   static CUStringObject numberWithoutGrouping ($number, CULocale $inLocale = null)
//   static CUStringObject numberScientific ($number, CULocale $inLocale = null)
//   static CUStringObject numberOrdinal ($ordinal, CULocale $inLocale = null)
//   static CUStringObject numberSpellOut ($number, CULocale $inLocale = null)
//   static CUStringObject percent ($number, CULocale $inLocale = null)
//   static CUStringObject currency ($number, CULocale $inLocale = null, $inCurrency = null)
//   static CUStringObject timeWithStyles (CTime $time, CTimeZone $timeZone, $dateStyle, $timeStyle,
//     CULocale $inLocale = null)
//   static CUStringObject timeWithPattern (CTime $time, CTimeZone $timeZone, $pattern, CULocale $inLocale = null)

// Date and Time Format Patterns.
//
// A date pattern is a string of characters, in which specific substrings of characters are replaced with date and time
// data from a calendar when formatting, or used to generate data for a calendar when parsing. The following are
// examples:
//
// +-------------------------------------------------------------------------------------------------------------------+
// |Pattern                                                 |Result (in a particular locale)                           |
// |--------------------------------------------------------+----------------------------------------------------------|
// |yyyy.MM.dd G 'at' HH:mm:ss zzz                          |1996.07.10 AD at 15:08:56 PDT                             |
// |--------------------------------------------------------+----------------------------------------------------------|
// |EEE, MMM d, ''yy                                        |Wed, July 10, '96                                         |
// |--------------------------------------------------------+----------------------------------------------------------|
// |h:mm a                                                  |12:08 PM                                                  |
// |--------------------------------------------------------+----------------------------------------------------------|
// |hh 'o''clock' a, zzzz                                   |12 o'clock PM, Pacific Daylight Time                      |
// |--------------------------------------------------------+----------------------------------------------------------|
// |K:mm a, z                                               |0:00 PM, PST                                              |
// |--------------------------------------------------------+----------------------------------------------------------|
// |yyyyy.MMMM.dd GGG hh:mm aaa                             |01996.July.10 AD 12:08 PM                                 |
// +-------------------------------------------------------------------------------------------------------------------+
//
// The Date Field Symbol Table below contains the characters used in patterns to show the appropriate formats for a
// given locale, such as yyyy for the year. Characters may be used multiple times. For example, if y is used for the
// year, "yy" might produce "99", whereas "yyyy" produces "1999". For most numerical fields, the number of characters
// specifies the field width. For example, if h is the hour, "h" might produce "5", but "hh" produces "05". For some
// characters, the count specifies whether an abbreviated or full form should be used, but may have other choices, as
// given below.
//
// http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
//
//                                                Date Field Symbol Table
// +-------------------------------------------------------------------------------------------------------------------+
// |Field  |Sym.|No. |Example                |Description                                                              |
// |-------+----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |1..3|AD                     |                                                                         |
// |       |    |----+-----------------------|Era - Replaced with the Era string for the current date. One to three    |
// |era    |G   |4   |Anno Domini            |letters for the abbreviated form, four letters for the long (wide) form, |
// |       |    |----+-----------------------|five for the narrow form.                                                |
// |       |    |5   |A                      |                                                                         |
// |-------+----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |Year. Normally the length specifies the padding, but for two letters it  |
// |       |    |    |                       |also specifies the maximum length. Example:                              |
// |       |    |    |                       |                                                                         |
// |       |    |    |                       |                   Year     y     yy yyy   yyyy  yyyyy                   |
// |       |y   |1..n|1996                   |                   AD 1     1     01 001   0001  00001                   |
// |       |    |    |                       |                   AD 12    12    12 012   0012  00012                   |
// |       |    |    |                       |                   AD 123   123   23 123   0123  00123                   |
// |       |    |    |                       |                   AD 1234  1234  34 1234  1234  01234                   |
// |       |    |    |                       |                   AD 12345 12345 45 12345 12345 12345                   |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |Year (in "Week of Year" based calendars). Normally the length specifies  |
// |       |    |    |                       |the padding, but for two letters it also specifies the maximum length.   |
// |       |Y   |1..n|1997                   |This year designation is used in ISO year-week calendar as defined by ISO|
// |       |    |    |                       |8601, but can be used in non-Gregorian based calendar systems where week |
// |       |    |    |                       |date processing is desired. May not always be the same value as calendar |
// |       |    |    |                       |year.                                                                    |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |Extended year. This is a single number designating the year of this      |
// |       |    |    |                       |calendar system, encompassing all supra-year fields. For example, for the|
// |       |u   |1..n|4601                   |Julian calendar system, year numbers are positive, with an era of BCE or |
// |       |    |    |                       |CE. An extended year value for the Julian calendar system assigns        |
// |       |    |    |                       |positive values to CE years and negative values to BCE years, with 1 BCE |
// |year   |    |    |                       |being year 0.                                                            |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |Cyclic year name. Calendars such as the Chinese lunar calendar (and      |
// |       |    |1..3|******                 |related calendars) and the Hindu calendars use 60-year cycles of year    |
// |       |    |    |                       |names. Use one through three letters for the abbreviated name, four for  |
// |       |    |----+-----------------------|the full (wide) name, or five for the narrow name (currently the data    |
// |       |U   |4   |(currently also ******)|only provides abbreviated names, which will be used for all requested    |
// |       |    |    |                       |name widths). If the calendar does not provide cyclic year name data, or |
// |       |    |----+-----------------------|if the year value to be formatted is out of the range of years for which |
// |       |    |5   |(currently also ******)|cyclic name data is provided, then numeric formatting is used (behaves   |
// |       |    |    |                       |like 'y').                                                               |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |Related Gregorian year. For non-Gregorian calendars, this corresponds to |
// |       |    |    |                       |the extended Gregorian year in which the calendar's year begins. Related |
// |       |    |    |                       |Gregorian years are often displayed, for example, when formatting dates  |
// |       |    |    |                       |in the Japanese calendar - e.g. "2012(******24)***1***15***" - or in the |
// |       |    |    |                       |Chinese calendar - e.g. "2012*********************". The related         |
// |       |r   |1..n|1996                   |Gregorian year is usually displayed using the "latn" numbering system,   |
// |       |    |    |                       |regardless of what numbering systems may be used for other parts of the  |
// |       |    |    |                       |formatted date. If the calendar's year is linked to the solar year       |
// |       |    |    |                       |(perhaps using leap months), then for that calendar the 'r' year will    |
// |       |    |    |                       |always be at a fixed offset from the 'u' year. For the Gregorian         |
// |       |    |    |                       |calendar, the 'r' year is the same as the 'u' year.                      |
// |-------+----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |1..2|02                     |                                                                         |
// |       |    |----+-----------------------|                                                                         |
// |       |    |3   |Q2                     |Quarter - Use one or two for the numerical quarter, three for the        |
// |       |Q   |----+-----------------------|abbreviation, four for the full (wide) name, or five for the narrow name.|
// |       |    |4   |2nd quarter            |                                                                         |
// |       |    |----+-----------------------|                                                                         |
// |       |    |5   |2                      |                                                                         |
// |quarter|----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |1..2|02                     |                                                                         |
// |       |    |----+-----------------------|                                                                         |
// |       |    |3   |Q2                     |Stand-Alone Quarter - Use one or two for the numerical quarter, three for|
// |       |q   |----+-----------------------|the abbreviation, four for the full name, or five for the narrow name.   |
// |       |    |4   |2nd quarter            |                                                                         |
// |       |    |----+-----------------------|                                                                         |
// |       |    |5   |2                      |                                                                         |
// |-------+----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |1..2|09                     |                                                                         |
// |       |    |----+-----------------------|                                                                         |
// |       |    |3   |Sept                   |Month - Use one or two for the numerical month, three for the            |
// |       |M   |----+-----------------------|abbreviation, four for the full (wide) name, or five for the narrow name.|
// |       |    |4   |September              |                                                                         |
// |       |    |----+-----------------------|                                                                         |
// |       |    |5   |S                      |                                                                         |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |1..2|09                     |                                                                         |
// |       |    |----+-----------------------|                                                                         |
// |month  |    |3   |Sept                   |Stand-Alone Month - Use one or two for the numerical month, three for the|
// |       |L   |----+-----------------------|abbreviation, four for the full (wide) name, or 5 for the narrow name.   |
// |       |    |4   |September              |                                                                         |
// |       |    |----+-----------------------|                                                                         |
// |       |    |5   |S                      |                                                                         |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |This pattern character is deprecated, and should be ignored in patterns. |
// |       |    |    |                       |It was originally intended to be used in combination with M to indicate  |
// |       |l   |1   |(nothing)              |placement of the symbol for leap month in the Chinese calendar. Placement|
// |       |    |    |                       |of that marker is now specified using locale-specific <monthPatterns>    |
// |       |    |    |                       |data, and formatting and parsing of that marker should be handled as part|
// |       |    |    |                       |of supporting the regular M and L pattern characters.                    |
// |-------+----+----+-----------------------+-------------------------------------------------------------------------|
// |       |w   |1..2|27                     |Week of Year.                                                            |
// |week   |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |W   |1   |3                      |Week of Month                                                            |
// |-------+----+----+-----------------------+-------------------------------------------------------------------------|
// |       |d   |1..2|1                      |Date - Day of the month                                                  |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |D   |1..3|345                    |Day of year                                                              |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |F   |1   |2                      |Day of Week in Month. The example is for the 2nd Wed in July             |
// |day    |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |Modified Julian day. This is different from the conventional Julian day  |
// |       |    |    |                       |number in two regards. First, it demarcates days at local zone midnight, |
// |       |g   |1..n|2451334                |rather than noon GMT. Second, it is a local number; that is, it depends  |
// |       |    |    |                       |on the local time zone. It can be thought of as a single number that     |
// |       |    |    |                       |encompasses all the date-related fields.                                 |
// |-------+----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |1..3|Tues                   |                                                                         |
// |       |    |----+-----------------------|                                                                         |
// |       |    |4   |Tuesday                |Day of week - Use one through three letters for the abbreviated day name,|
// |       |E   |----+-----------------------|four for the full (wide) name, five for the narrow name, or six for the  |
// |       |    |5   |T                      |short name.                                                              |
// |       |    |----+-----------------------|                                                                         |
// |       |    |6   |Tu                     |                                                                         |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |1..2|2                      |                                                                         |
// |       |    |----+-----------------------|                                                                         |
// |       |    |3   |Tues                   |                                                                         |
// |       |    |----+-----------------------|Local day of week. Same as E except adds a numeric value that will depend|
// |week   |e   |4   |Tuesday                |on the local starting day of the week, using one or two letters. For this|
// |day    |    |----+-----------------------|example, Monday is the first day of the week.                            |
// |       |    |5   |T                      |                                                                         |
// |       |    |----+-----------------------|                                                                         |
// |       |    |6   |Tu                     |                                                                         |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |1   |2                      |                                                                         |
// |       |    |----+-----------------------|                                                                         |
// |       |    |3   |Tues                   |                                                                         |
// |       |    |----+-----------------------|Stand-Alone local day of week - Use one letter for the local numeric     |
// |       |c   |4   |Tuesday                |value (same as 'e'), three for the abbreviated day name, four for the    |
// |       |    |----+-----------------------|full (wide) name, five for the narrow name, or six for the short name.   |
// |       |    |5   |T                      |                                                                         |
// |       |    |----+-----------------------|                                                                         |
// |       |    |6   |Tu                     |                                                                         |
// |-------+----+----+-----------------------+-------------------------------------------------------------------------|
// |period |a   |1   |AM                     |AM or PM                                                                 |
// |-------+----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |Hour [1-12]. When used in skeleton data or in a skeleton passed in an API|
// |       |h   |1..2|11                     |for flexible date pattern generation, it should match the 12-hour-cycle  |
// |       |    |    |                       |format preferred by the locale (h or K); it should not match a           |
// |       |    |    |                       |24-hour-cycle format (H or k). Use hh for zero padding.                  |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |Hour [0-23]. When used in skeleton data or in a skeleton passed in an API|
// |       |H   |1..2|13                     |for flexible date pattern generation, it should match the 24-hour-cycle  |
// |       |    |    |                       |format preferred by the locale (H or k); it should not match a           |
// |       |    |    |                       |12-hour-cycle format (h or K). Use HH for zero padding.                  |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |K   |1..2|0                      |Hour [0-11]. When used in a skeleton, only matches K or h, see above. Use|
// |       |    |    |                       |KK for zero padding.                                                     |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |k   |1..2|24                     |Hour [1-24]. When used in a skeleton, only matches k or H, see above. Use|
// |       |    |    |                       |kk for zero padding.                                                     |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |This is a special-purpose symbol. It must not occur in pattern or        |
// |hour   |    |    |                       |skeleton data. Instead, it is reserved for use in skeletons passed to    |
// |       |    |    |                       |APIs doing flexible date pattern generation. In such a context, it       |
// |       |    |    |                       |requests the preferred hour format for the locale (h, H, K, or k), as    |
// |       |j   |1..2|n/a                    |determined by whether h, H, K, or k is used in the standard short time   |
// |       |    |    |                       |format for the locale. In the implementation of such an API, 'j' must be |
// |       |    |    |                       |replaced by h, H, K, or k before beginning a match against               |
// |       |    |    |                       |availableFormats data. Note that use of 'j' in a skeleton passed to an   |
// |       |    |    |                       |API is the only way to have a skeleton request a locale's preferred time |
// |       |    |    |                       |cycle type (12-hour or 24-hour).                                         |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |This is a special-purpose symbol. It must not occur in pattern or        |
// |       |    |    |                       |skeleton data. Instead, it is reserved for use in skeletons passed to    |
// |       |    |    |                       |APIs doing flexible date pattern generation. In such a context, like 'j',|
// |       |J   |1..2|n/a                    |it requests the preferred hour format for the locale (h, H, K, or k).    |
// |       |    |    |                       |However, unlike 'j', it requests no "am/pm" marker (It is typically used |
// |       |    |    |                       |where there is enough context that that is not necessary). For example,  |
// |       |    |    |                       |in en_US with "Jmm", 13:00 would appear as "1:00", while with "jmm", it  |
// |       |    |    |                       |would appear as "1:00 PM".                                               |
// |-------+----+----+-----------------------+-------------------------------------------------------------------------|
// |minute |m   |1..2|59                     |Minute. Use one or two for zero padding.                                 |
// |-------+----+----+-----------------------+-------------------------------------------------------------------------|
// |       |s   |1..2|12                     |Second. Use one or two for zero padding.                                 |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |Fractional Second - truncates (like other time fields) to the count of   |
// |       |S   |1..n|3456                   |letters. (example shows display using pattern SSSS for seconds value     |
// |       |    |    |                       |12.34567)                                                                |
// |second |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |Milliseconds in day. This field behaves exactly like a composite of all  |
// |       |    |    |                       |time-related fields, not including the zone fields. As such, it also     |
// |       |A   |1..n|69540000               |reflects discontinuities of those fields on DST transition days. On a day|
// |       |    |    |                       |of DST onset, it will jump forward. On a day of DST cessation, it will   |
// |       |    |    |                       |jump backward. This reflects the fact that is must be combined with the  |
// |       |    |    |                       |offset field to obtain a unique local time value.                        |
// |-------+----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |1..3|PDT                    |The short specific non-location format. Where that is unavailable, falls |
// |       |    |    |                       |back to the short localized GMT format ("O").                            |
// |       |z   |----+-----------------------+-------------------------------------------------------------------------|
// |       |    |4   |Pacific Daylight Time  |The long specific non-location format. Where that is unavailable, falls  |
// |       |    |    |                       |back to the long localized GMT format ("OOOO").                          |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |The ISO8601 basic format with hours, minutes and optional seconds fields.|
// |       |    |1..3|-0800                  |The format is equivalent to RFC 822 zone format (when optional seconds   |
// |       |    |    |                       |field is absent). This is equivalent to the "xxxx" specifier.            |
// |       |    |----+-----------------------+-------------------------------------------------------------------------|
// |       |Z   |4   |GMT-8:00               |The long localized GMT format. This is equivalent to the "OOOO"          |
// |       |    |    |                       |specifier.                                                               |
// |       |    |----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |-08:00                 |The ISO8601 extended format with hours, minutes and optional seconds     |
// |       |    |5   |-07:52:58              |fields. The ISO8601 UTC indicator "Z" is used when local time offset is  |
// |       |    |    |                       |0. This is equivalent to the "XXXXX" specifier.                          |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |1   |GMT-8                  |The short localized GMT format.                                          |
// |       |O   |----+-----------------------+-------------------------------------------------------------------------|
// |       |    |4   |GMT-08:00              |The long localized GMT format.                                           |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |The short generic non-location format. Where that is unavailable, falls  |
// |       |    |1   |PT                     |back to the generic location format ("VVVV"), then the short localized   |
// |       |v   |    |                       |GMT format as the final fallback.                                        |
// |       |    |----+-----------------------+-------------------------------------------------------------------------|
// |       |    |4   |Pacific Time           |The long generic non-location format. Where that is unavailable, falls   |
// |       |    |    |                       |back to generic location format ("VVVV").                                |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |The short time zone ID. Where that is unavailable, the special short time|
// |       |    |    |                       |zone ID unk (Unknown Zone) is used.                                      |
// |       |    |1   |uslax                  |Note: This specifier was originally used for a variant of the short      |
// |       |    |    |                       |specific non-location format, but it was deprecated in the later version |
// |       |    |    |                       |of this specification. In CLDR 23, the definition of the specifier was   |
// |       |    |    |                       |changed to designate a short time zone ID.                               |
// |       |    |----+-----------------------+-------------------------------------------------------------------------|
// |       |    |2   |America/Los_Angeles    |The long time zone ID.                                                   |
// |       |V   |----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |The exemplar city (location) for the time zone. Where that is            |
// |       |    |3   |Los Angeles            |unavailable, the localized exemplar city name for the special zone       |
// |       |    |    |                       |Etc/Unknown is used as the fallback (for example, "Unknown City").       |
// |       |    |----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |                       |The generic location format. Where that is unavailable, falls back to the|
// |zone   |    |    |                       |long localized GMT format ("OOOO"; Note: Fallback is only necessary with |
// |       |    |4   |Los Angeles Time       |a GMT-style Time Zone ID, like Etc/GMT-830.)                             |
// |       |    |    |                       |This is especially useful when presenting possible timezone choices for  |
// |       |    |    |                       |user selection, since the naming is more uniform than the "v" format.    |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |-08                    |The ISO8601 basic format with hours field and optional minutes field. The|
// |       |    |1   |+0530                  |ISO8601 UTC indicator "Z" is used when local time offset is 0. (The same |
// |       |    |    |Z                      |as x, plus "Z".)                                                         |
// |       |    |----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |-0800                  |The ISO8601 basic format with hours and minutes fields. The ISO8601 UTC  |
// |       |    |2   |Z                      |indicator "Z" is used when local time offset is 0. (The same as xx, plus |
// |       |    |    |                       |"Z".)                                                                    |
// |       |    |----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |-08:00                 |The ISO8601 extended format with hours and minutes fields. The ISO8601   |
// |       |    |3   |Z                      |UTC indicator "Z" is used when local time offset is 0. (The same as xxx, |
// |       |X   |    |                       |plus "Z".)                                                               |
// |       |    |----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |-0800                  |The ISO8601 basic format with hours, minutes and optional seconds fields.|
// |       |    |4   |-075258                |The ISO8601 UTC indicator "Z" is used when local time offset is 0. (The  |
// |       |    |    |Z                      |same as xxxx, plus "Z".)                                                 |
// |       |    |    |                       |Note: The seconds field is not supported by the ISO8601 specification.   |
// |       |    |----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |-08:00                 |The ISO8601 extended format with hours, minutes and optional seconds     |
// |       |    |5   |-07:52:58              |fields. The ISO8601 UTC indicator "Z" is used when local time offset is  |
// |       |    |    |Z                      |0. (The same as xxxxx, plus "Z".)                                        |
// |       |    |    |                       |Note: The seconds field is not supported by the ISO8601 specification.   |
// |       |----+----+-----------------------+-------------------------------------------------------------------------|
// |       |    |1   |-08                    |The ISO8601 basic format with hours field and optional minutes field.    |
// |       |    |    |+0530                  |(The same as X, minus "Z".)                                              |
// |       |    |----+-----------------------+-------------------------------------------------------------------------|
// |       |    |2   |-0800                  |The ISO8601 basic format with hours and minutes fields. (The same as XX, |
// |       |    |    |                       |minus "Z".)                                                              |
// |       |    |----+-----------------------+-------------------------------------------------------------------------|
// |       |    |3   |-08:00                 |The ISO8601 extended format with hours and minutes fields. (The same as  |
// |       |x   |    |                       |XXX, minus "Z".)                                                         |
// |       |    |----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |-0800                  |The ISO8601 basic format with hours, minutes and optional seconds fields.|
// |       |    |4   |-075258                |(The same as XXXX, minus "Z".)                                           |
// |       |    |    |                       |Note: The seconds field is not supported by the ISO8601 specification.   |
// |       |    |----+-----------------------+-------------------------------------------------------------------------|
// |       |    |    |-08:00                 |The ISO8601 extended format with hours, minutes and optional seconds     |
// |       |    |5   |-07:52:58              |fields. (The same as XXXXX, minus "Z".)                                  |
// |       |    |    |                       |Note: The seconds field is not supported by the ISO8601 specification.   |
// +-------------------------------------------------------------------------------------------------------------------+

class CUFormat extends CRootClass
{
    // Date and time format styles.
    /**
     * `enum` The short-length style for date/time formatting.
     *
     * @var enum
     */
    const STYLE_SHORT = 0;
    /**
     * `enum` The medium-length style for date/time formatting.
     *
     * @var enum
     */
    const STYLE_MEDIUM = 1;
    /**
     * `enum` The long-length style for date/time formatting.
     *
     * @var enum
     */
    const STYLE_LONG = 2;
    /**
     * `enum` The full-length style for date/time formatting.
     *
     * @var enum
     */
    const STYLE_FULL = 3;

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Formats a number according to the formatting rules used in the default or some other locale, putting grouping
     * separators where applicable, and returns the formatted string.
     *
     * @param  number $number The number to be formatted.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * number is to be formatted.
     *
     * @return CUStringObject A string with the formatted number.
     */

    public static function number ($number, CULocale $inLocale = null)
    {
        assert( 'is_number($number)', vs(isset($this), get_defined_vars()) );

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $numberFormatter = new NumberFormatter($locale, NumberFormatter::DECIMAL);
        self::doFracLength($numberFormatter);
        $strNumber = $numberFormatter->format($number);
        if ( is_cstring($strNumber) )
        {
            return $strNumber;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Formats a number according to the formatting rules used in the default or some other locale, without any
     * grouping separators put, and returns the formatted string.
     *
     * @param  number $number The number to be formatted.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * number is to be formatted.
     *
     * @return CUStringObject A string with the formatted number.
     */

    public static function numberWithoutGrouping ($number, CULocale $inLocale = null)
    {
        assert( 'is_number($number)', vs(isset($this), get_defined_vars()) );

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $numberFormatter = new NumberFormatter($locale, NumberFormatter::DECIMAL);
        self::doFracLength($numberFormatter);
        $numberFormatter->setAttribute(NumberFormatter::GROUPING_USED, 0);
        $strNumber = $numberFormatter->format($number);
        if ( is_cstring($strNumber) )
        {
            return $strNumber;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Formats a number in a scientific fashion and according to the formatting rules used in the default or some other
     * locale and returns the formatted string.
     *
     * @param  number $number The number to be formatted.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * number is to be formatted.
     *
     * @return CUStringObject A string with the formatted number.
     */

    public static function numberScientific ($number, CULocale $inLocale = null)
    {
        assert( 'is_number($number)', vs(isset($this), get_defined_vars()) );

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $numberFormatter = new NumberFormatter($locale, NumberFormatter::SCIENTIFIC);
        self::doFracLength($numberFormatter);
        $strNumber = $numberFormatter->format($number);
        if ( is_cstring($strNumber) )
        {
            return $strNumber;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Formats an integer as an ordinal number and according to the language of the default or some other locale and
     * returns the formatted string.
     *
     * @param  int $ordinal The number to be formatted.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * number is to be formatted.
     *
     * @return CUStringObject A string with the formatted number.
     */

    public static function numberOrdinal ($ordinal, CULocale $inLocale = null)
    {
        assert( 'is_int($ordinal)', vs(isset($this), get_defined_vars()) );
        assert( '$ordinal >= 0', vs(isset($this), get_defined_vars()) );

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $numberFormatter = new NumberFormatter($locale, NumberFormatter::ORDINAL);
        $strOrdinal = $numberFormatter->format($ordinal);
        if ( is_cstring($strOrdinal) )
        {
            return $strOrdinal;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Spells out a number according to the language of the default or some other locale and returns the string.
     *
     * @param  number $number The number to be spelled out.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * number is to be spelled out.
     *
     * @return CUStringObject A string with the spelled out number.
     */

    public static function numberSpellOut ($number, CULocale $inLocale = null)
    {
        assert( 'is_number($number)', vs(isset($this), get_defined_vars()) );

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $numberFormatter = new NumberFormatter($locale, NumberFormatter::SPELLOUT);
        $strNumber = $numberFormatter->format($number);
        if ( is_cstring($strNumber) )
        {
            return $strNumber;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Formats a number as a percentage and according to the formatting rules used in the default or some other locale
     * and returns the formatted string.
     *
     * The value of `0.0` of the input number corresponds to 0% and the value of `1.0` corresponds to 100%.
     *
     * @param  number $number The number to be formatted. Can be from `0.0` to `1.0`.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * number is to be formatted.
     *
     * @return CUStringObject A string with the formatted number.
     */

    public static function percent ($number, CULocale $inLocale = null)
    {
        assert( 'is_number($number)', vs(isset($this), get_defined_vars()) );

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $numberFormatter = new NumberFormatter($locale, NumberFormatter::PERCENT);
        self::doFracLength($numberFormatter);
        $strNumber = $numberFormatter->format($number);
        if ( is_cstring($strNumber) )
        {
            return $strNumber;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In the default or some other locale, formats a number as an amount of money in the currency that is used by the
     * locale's main country and returns the formatted string.
     *
     * @param  number $number The number to be formatted.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * number is to be formatted.
     * @param  string $inCurrency **OPTIONAL. Default is** *the locale's default currency*. The three-letter currency
     * code.
     *
     * @return CUStringObject A string with the formatted number.
     */

    public static function currency ($number, CULocale $inLocale = null, $inCurrency = null)
    {
        assert( 'is_number($number) && (!isset($inCurrency) || is_cstring($inCurrency))',
            vs(isset($this), get_defined_vars()) );

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $numberFormatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        self::doFracLength($numberFormatter, true);
        if ( !isset($inCurrency) )
        {
            $strNumber = $numberFormatter->format($number);
        }
        else
        {
            $strNumber = $numberFormatter->formatCurrency($number, $inCurrency);
        }
        if ( is_cstring($strNumber) )
        {
            return $strNumber;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Formats a point in time as a string in a specified time zone and according to the formatting rules used in the
     * default or some other locale, displaying the date and time parts to the extent of specified predefined styles,
     * and returns the formatted string.
     *
     * @param  CTime $time The point in time to be formatted.
     * @param  CTimeZone $timeZone The time zone in which the components in the resulting string are to appear.
     * @param  enum $dateStyle The display style of the date part in the formatted string. Can be `STYLE_SHORT`,
     * `STYLE_MEDIUM`, `STYLE_LONG`, or `STYLE_FULL`.
     * @param  enum $timeStyle The display style of the time part in the formatted string. Can be `STYLE_SHORT`,
     * `STYLE_MEDIUM`, `STYLE_LONG`, or `STYLE_FULL`.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * point in time is to be formatted.
     *
     * @return CUStringObject A string with the formatted point in time.
     */

    public static function timeWithStyles (CTime $time, CTimeZone $timeZone, $dateStyle, $timeStyle,
        CULocale $inLocale = null)
    {
        assert( 'is_enum($dateStyle) && is_enum($timeStyle)', vs(isset($this), get_defined_vars()) );

        $styles = CArray::fromElements($dateStyle, $timeStyle);
        for ($i = 0; $i < 2; $i++)
        {
            switch ( $styles[$i] )
            {
            case self::STYLE_SHORT:
                $styles[$i] = IntlDateFormatter::SHORT;
                break;
            case self::STYLE_MEDIUM:
                $styles[$i] = IntlDateFormatter::MEDIUM;
                break;
            case self::STYLE_LONG:
                $styles[$i] = IntlDateFormatter::LONG;
                break;
            case self::STYLE_FULL:
                $styles[$i] = IntlDateFormatter::FULL;
                break;
            default:
                assert( 'false', vs(isset($this), get_defined_vars()) );
                break;
            }
        }
        $idfDateStyle = $styles[0];
        $idfTimeStyle = $styles[1];

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $intlDateFormatter = new IntlDateFormatter($locale, $idfDateStyle, $idfTimeStyle, $timeZone->ITimeZone());
        $strTime = $intlDateFormatter->format($time->UTime());
        if ( is_cstring($strTime) )
        {
            return $strTime;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Formats a point in time as a string in a specified time zone according to a specified pattern and the formatting
     * rules used in the default or some other locale and returns the formatted string.
     *
     * The formatting patterns that you can use to format a point in time with this method are described in
     * [Date Format Patterns](http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Format_Patterns) of the Unicode
     * Technical Standard #35.
     *
     * @param  CTime $time The point in time to be formatted.
     * @param  CTimeZone $timeZone The time zone in which the components in the resulting string are to appear.
     * @param  string $pattern The formatting pattern.
     * @param  CULocale $inLocale **OPTIONAL. Default is** *the application's default locale*. The locale in which the
     * point in time is to be formatted.
     *
     * @return CUStringObject A string with the formatted point in time.
     */

    public static function timeWithPattern (CTime $time, CTimeZone $timeZone, $pattern,
        CULocale $inLocale = null)
    {
        assert( 'is_cstring($pattern)', vs(isset($this), get_defined_vars()) );

        $locale = ( isset($inLocale) ) ? $inLocale->name() : CULocale::defaultLocaleName();
        $intlDateFormatter = new IntlDateFormatter($locale, IntlDateFormatter::FULL, IntlDateFormatter::FULL,
            $timeZone->ITimeZone(), null, $pattern);
        $strTime = $intlDateFormatter->format($time->UTime());
        if ( is_cstring($strTime) )
        {
            return $strTime;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    protected static function doFracLength (NumberFormatter $numberFormatter, $forCurrency = false)
    {
        if ( !$forCurrency )
        {
            $numberFormatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 0);
        }
        $numberFormatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, self::$ms_maxNumFractionDigits);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    protected static $ms_maxNumFractionDigits = 64;
}
