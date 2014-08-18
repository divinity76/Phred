<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * The hub for static methods that take care of ASCII strings.
 *
 * **In the OOP mode, you would likely never need to use this class.**
 *
 * **You can refer to this class by its alias, which is** `Str`.
 *
 * An ASCII string is a string of characters where each character is a byte with a value in the range from 0 (0x00) to
 * 127 (0x7F). Unlike Unicode strings, which are mostly suitable for human languages, ASCII strings are typically used
 * by computers to exchange information with other computers, markup, coding, and various metadata. When an ASCII
 * string appears with a human language in some of its part, that language is likely to be English.
 *
 * When deciding between the `compare...` methods to determine the order in which strings should appear in a sorted
 * list or any other place where strings need to be presented to a human being in a sorted fashion, `compareNatCi`
 * method is usually most preferable. Since ASCII and Unicode do not share the same view on string comparison, Unicode
 * strings, which are served by the CUString class, get compared with the `compare...` methods in a way that is
 * different from how ASCII strings get compared with the same-named methods of this class.
 */

// Method signatures:
//   static bool isValid ($string)
//   static string sanitize ($string)
//   static string fromBool10 ($value)
//   static string fromBoolTf ($value)
//   static string fromBoolYn ($value)
//   static string fromBoolOo ($value)
//   static string fromInt ($value)
//   static string fromFloat ($value)
//   static string fromCharCode ($code)
//   static bool toBool ($string)
//   static bool toBoolFrom10 ($string)
//   static bool toBoolFromTf ($string)
//   static bool toBoolFromYn ($string)
//   static bool toBoolFromOo ($string)
//   static int toInt ($string)
//   static int toIntFromHex ($string)
//   static int toIntFromBase ($string, $base)
//   static float toFloat ($string)
//   static int toCharCode ($char)
//   static string toCharCodeHex ($char)
//   static string toEscString ($string)
//   static int length ($string)
//   static bool isEmpty ($string)
//   static bool equals ($string, $toString)
//   static bool equalsCi ($string, $toString)
//   static int compare ($string, $toString)
//   static int compareCi ($string, $toString)
//   static int compareNat ($string, $toString)
//   static int compareNatCi ($string, $toString)
//   static int levenDist ($string, $toString)
//   static string metaphoneKey ($string)
//   static int metaphoneDist ($string, $toString)
//   static string toLowerCase ($string)
//   static string toUpperCase ($string)
//   static string toUpperCaseFirst ($string)
//   static string toTitleCase ($string)
//   static bool startsWith ($string, $withString)
//   static bool startsWithCi ($string, $withString)
//   static bool endsWith ($string, $withString)
//   static bool endsWithCi ($string, $withString)
//   static int indexOf ($string, $ofString, $startPos = 0)
//   static int indexOfCi ($string, $ofString, $startPos = 0)
//   static int lastIndexOf ($string, $ofString, $startPos = 0)
//   static int lastIndexOfCi ($string, $ofString, $startPos = 0)
//   static bool find ($string, $whatString, $startPos = 0)
//   static bool findCi ($string, $whatString, $startPos = 0)
//   static bool isSubsetOf ($string, $ofCharSet)
//   static string substr ($string, $startPos, $length = null)
//   static string substring ($string, $startPos, $endPos)
//   static int numSubstrings ($string, $substring, $startPos = 0)
//   static CArray split ($string, $delimiterOrDelimiters)
//   static CArray splitIntoChars ($string)
//   static string trimStart ($string)
//   static string trimEnd ($string)
//   static string trim ($string)
//   static string normSpacing ($string)
//   static string normNewlines ($string, $newline = self::NEWLINE)
//   static string padStart ($string, $paddingString, $newLength)
//   static string padEnd ($string, $paddingString, $newLength)
//   static string stripStart ($string, $prefixOrPrefixes)
//   static string stripStartCi ($string, $prefixOrPrefixes)
//   static string stripEnd ($string, $suffixOrSuffixes)
//   static string stripEndCi ($string, $suffixOrSuffixes)
//   static string insert ($string, $atPos, $insertString)
//   static string replaceSubstring ($string, $startPos, $length, $with)
//   static string replaceSubstringByRange ($string, $startPos, $endPos, $with)
//   static string removeSubstring ($string, $startPos, $length)
//   static string removeSubstringByRange ($string, $startPos, $endPos)
//   static string replace ($string, $what, $with, &$quantity = null)
//   static string replaceCi ($string, $what, $with, &$quantity = null)
//   static string remove ($string, $what, &$quantity = null)
//   static string removeCi ($string, $what, &$quantity = null)
//   static string shuffle ($string)
//   static string wordWrap ($string, $width, $breakSpacelessLines = false, $newline = self::NEWLINE)
//   static string decToHex ($number)
//   static string hexToDec ($number)
//   static string numberToBase ($number, $fromBase, $toBase)
//   static string repeat ($string, $times)

class CString extends CRootClass implements IEqualityAndOrderStatic
{
    // The default newline format and other common newline formats.
    /**
     * `string` The default newline format, which is LF (0x0A).
     *
     * @var string
     */
    const NEWLINE = self::NEWLINE_LF;
    /**
     * `string` LF newline (0x0A). Used by Linux/Unix and OS X.
     *
     * @var string
     */
    const NEWLINE_LF = "\x0A";
    /**
     * `string` CRLF newline (0x0D, 0x0A). Used by Windows.
     *
     * @var string
     */
    const NEWLINE_CRLF = "\x0D\x0A";
    /**
     * `string` CR newline (0x0D).
     *
     * @var string
     */
    const NEWLINE_CR = "\x0D";

    /**
     * `string` The regular expression pattern used in trimming and spacing normalization.
     *
     * @var string
     */
    const TRIMMING_AND_SPACING_NORM_SUBJECT_RE = "[\\x00-\\x20\\x7F-\\xFF]";

    /**
     * `string` The regular expression pattern used in newline normalization.
     *
     * @var string
     */
    const NL_NORM_SUBJECT_RE = "\\x0D\\x0A|\\x0A|\\x0B|\\x0C|\\x0D";  // CRLF goes first because of its length

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string is a valid ASCII string.
     *
     * A valid ASCII string can only contain characters (bytes) with codes that do not exceed 127 (0x7F).
     *
     * @param  string $string The string to be looked into.
     *
     * @return bool `true` if the string is a valid ASCII string, `false` otherwise.
     */

    public static function isValid ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return ( preg_match("/^[\\x00-\\x7F]*\\z/", $string) === 1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces any invalid character in an ASCII string with a question mark and returns the new string.
     *
     * An invalid character (byte) in an ASCII string is any character with a code that exceeds 127 (0x7F).
     *
     * @param  string $string The string to be sanitized.
     *
     * @return string The sanitized string.
     */

    public static function sanitize ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return preg_replace("/[\\x80-\\xFF]/", "?", $string);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "1" for `true` and as "0" for `false`.
     *
     * @param  bool $value The value to be converted.
     *
     * @return string "1" for `true`, "0" for `false`.
     */

    public static function fromBool10 ($value)
    {
        assert( 'is_bool($value)', vs(isset($this), get_defined_vars()) );
        return ( $value ) ? "1" : "0";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "true" for `true` and as "false" for `false`.
     *
     * @param  bool $value The value to be converted.
     *
     * @return string "true" for `true`, "false" for `false`.
     */

    public static function fromBoolTf ($value)
    {
        assert( 'is_bool($value)', vs(isset($this), get_defined_vars()) );
        return ( $value ) ? "true" : "false";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "yes" for `true` and as "no" for `false`.
     *
     * @param  bool $value The value to be converted.
     *
     * @return string "yes" for `true`, "no" for `false`.
     */

    public static function fromBoolYn ($value)
    {
        assert( 'is_bool($value)', vs(isset($this), get_defined_vars()) );
        return ( $value ) ? "yes" : "no";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a boolean value into a string, as "on" for `true` and as "off" for `false`.
     *
     * @param  bool $value The value to be converted.
     *
     * @return string "on" for `true`, "off" for `false`.
     */

    public static function fromBoolOo ($value)
    {
        assert( 'is_bool($value)', vs(isset($this), get_defined_vars()) );
        return ( $value ) ? "on" : "off";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts an integer value into a string.
     *
     * @param  int $value The value to be converted.
     *
     * @return string The textual representation of the integer value.
     */

    public static function fromInt ($value)
    {
        assert( 'is_int($value)', vs(isset($this), get_defined_vars()) );
        return (string)$value;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a floating-point value into a string.
     *
     * @param  float $value The value to be converted.
     *
     * @return string The textual representation of the floating-point value.
     */

    public static function fromFloat ($value)
    {
        assert( 'is_float($value)', vs(isset($this), get_defined_vars()) );
        return (string)$value;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a character by its code.
     *
     * @param  int $code The ASCII character code.
     *
     * @return string The ASCII character with the code specified.
     */

    public static function fromCharCode ($code)
    {
        assert( 'is_int($code)', vs(isset($this), get_defined_vars()) );
        assert( '0 <= $code && $code <= 0x7F', vs(isset($this), get_defined_vars()) );

        return chr($code);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value.
     *
     * Any string that is "1", "true", "yes", or "on", regardless of the letter case, is interpreted as `true` and any
     * other string is interpreted as `false`.
     *
     * @param  string $string The string to be converted.
     *
     * @return bool `true` for "1", "true", "yes", and "on", `false` for any other string.
     */

    public static function toBool ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );

        return (
            self::equals($string, "1") ||
            self::equalsCi($string, "true") ||
            self::equalsCi($string, "yes") ||
            self::equalsCi($string, "on") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "1" and as `false` for "0" or any other string.
     *
     * @param  string $string The string to be converted.
     *
     * @return bool `true` for "1", `false` for any other string.
     */

    public static function toBoolFrom10 ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return self::equals($string, "1");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "true" and as `false` for "false" or any other string.
     *
     * The conversion is case-insensitive.
     *
     * @param  string $string The string to be converted.
     *
     * @return bool `true` for "true", `false` for any other string.
     */

    public static function toBoolFromTf ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return self::equalsCi($string, "true");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "yes" and as `false` for "no" or any other string.
     *
     * The conversion is case-insensitive.
     *
     * @param  string $string The string to be converted.
     *
     * @return bool `true` for "yes", `false` for any other string.
     */

    public static function toBoolFromYn ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return self::equalsCi($string, "yes");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into a boolean value, as `true` for "on" and as `false` for "off" or any other string.
     *
     * The conversion is case-insensitive.
     *
     * @param  string $string The string to be converted.
     *
     * @return bool `true` for "on", `false` for any other string.
     */

    public static function toBoolFromOo ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return self::equalsCi($string, "on");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string into the corresponding integer value.
     *
     * @param  string $string The string to be converted.
     *
     * @return int The integer value represented by the string.
     */

    public static function toInt ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return (int)$string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a hexadecimal integer into the corresponding integer value.
     *
     * The string may be prefixed with "0x".
     *
     * @param  string $string The string to be converted.
     *
     * @return int The integer value represented by the string.
     */

    public static function toIntFromHex ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return intval($string, 16);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with an arbitrary-base integer into the corresponding integer value.
     *
     * The string may be prefixed with "0x" for the base of 16.
     *
     * @param  string $string The string to be converted.
     * @param  int $base The base in which the integer is represented by the string. Can be a number in the range from
     * 2 to 36.
     *
     * @return int The integer value represented by the string.
     */

    public static function toIntFromBase ($string, $base)
    {
        assert( 'is_cstring($string) && is_int($base)', vs(isset($this), get_defined_vars()) );
        assert( '2 <= $base && $base <= 36', vs(isset($this), get_defined_vars()) );

        return intval($string, $base);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a floating-point number into the corresponding floating-point value.
     *
     * The number can be using a scientific notation, such as "2.5e-1".
     *
     * @param  string $string The string to be converted.
     *
     * @return float The floating-point value represented by the string.
     */

    public static function toFloat ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return (float)$string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the code of a specified character, as an integer.
     *
     * @param  string $char The character.
     *
     * @return int The ASCII code of the character.
     */

    public static function toCharCode ($char)
    {
        assert( 'is_cstring($char)', vs(isset($this), get_defined_vars()) );
        assert( 'strlen($char) == 1', vs(isset($this), get_defined_vars()) );

        return ord($char);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the code of a specified character, as a hexadecimal string.
     *
     * The returned string is always two characters in length.
     *
     * @param  string $char The character.
     *
     * @return string The hexadecimal ASCII code of the character.
     */

    public static function toCharCodeHex ($char)
    {
        assert( 'is_cstring($char)', vs(isset($this), get_defined_vars()) );
        return self::padStart(self::decToHex((string)self::toCharCode($char)), "0", 2);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Escapes all the characters in a string and returns the new string.
     *
     * Each character is replaced with "\x" followed by the two-digit hexadecimal code of the character.
     *
     * @param  string $string The string to be escaped.
     *
     * @return string The escaped string.
     */

    public static function toEscString ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );

        $resString = "";
        for ($i = 0; $i < strlen($string); $i++)
        {
            $resString .= "\\x" . self::toCharCodeHex($string[$i]);
        }
        return $resString;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many characters there are in a string.
     *
     * @param  string $string The string to be looked into.
     *
     * @return int The string's length.
     */

    public static function length ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return strlen($string);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string is empty.
     *
     * @param  string $string The string to be looked into.
     *
     * @return bool `true` if the string is empty, `false` otherwise.
     */

    public static function isEmpty ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return ( $string === "" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if two strings are equal, comparing them case-sensitively.
     *
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     *
     * @return bool `true` if the two strings are equal, taking into account the letter case of the characters, and
     * `false` otherwise.
     */

    public static function equals ($string, $toString)
    {
        assert( 'is_cstring($string) && is_cstring($toString)', vs(isset($this), get_defined_vars()) );
        return ( $string === $toString );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if two strings are equal, comparing them case-insensitively.
     *
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     *
     * @return bool `true` if the two strings are equal, ignoring the letter case of the characters, and `false`
     * otherwise.
     */

    public static function equalsCi ($string, $toString)
    {
        assert( 'is_cstring($string) && is_cstring($toString)', vs(isset($this), get_defined_vars()) );
        return ( strcasecmp($string, $toString) == 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order and comparing the strings case-sensitively.
     *
     * **NOTE.** In ASCII, all uppercase goes before all lowercase, so you may consider using `compareCi` method
     * instead.
     *
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, taking into account the letter case of the characters.
     *
     * @link   #method_compareCi compareCi
     */

    public static function compare ($string, $toString)
    {
        assert( 'is_cstring($string) && is_cstring($toString)', vs(isset($this), get_defined_vars()) );
        return CMathi::sign(strcmp($string, $toString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order and comparing the strings case-insensitively.
     *
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, ignoring the letter case of the characters.
     */

    public static function compareCi ($string, $toString)
    {
        assert( 'is_cstring($string) && is_cstring($toString)', vs(isset($this), get_defined_vars()) );
        return CMathi::sign(strcasecmp($string, $toString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order, comparing the strings case-sensitively, and using natural order comparison.
     *
     * To illustrate natural order with an example, the strings "a100" and "a20" would get ordered as such with
     * `compare` method, but as "a20" and "a100" with this method, which is the order a human being would choose.
     *
     * **NOTE.** In ASCII, all uppercase goes before all lowercase, so you may consider using `compareNatCi` method
     * instead.
     *
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, taking into account the letter case of the characters.
     *
     * @link   #method_compareNatCi compareNatCi
     */

    public static function compareNat ($string, $toString)
    {
        assert( 'is_cstring($string) && is_cstring($toString)', vs(isset($this), get_defined_vars()) );
        return CMathi::sign(strnatcmp($string, $toString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines the order in which two strings should appear in a place where it matters, assuming the ascending
     * order, comparing the strings case-insensitively, and using natural order comparison.
     *
     * To illustrate natural order with an example, the strings "a100" and "a20" would get ordered as such with
     * `compareCi` method, but as "a20" and "a100" with this method, which is the order a human being would choose.
     *
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     *
     * @return int `-1` if the first string should go before the second string, `1` if the other way around, and `0` if
     * the two strings are equal, ignoring the letter case of the characters.
     */

    public static function compareNatCi ($string, $toString)
    {
        assert( 'is_cstring($string) && is_cstring($toString)', vs(isset($this), get_defined_vars()) );
        return CMathi::sign(strnatcasecmp($string, $toString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the Levenshtein distance calculated between two strings.
     *
     * For any two strings, the [Levenshtein distance](http://en.wikipedia.org/wiki/Levenshtein_distance) is the total
     * number of insert, replace, and delete operations required to transform the first string into the second one.
     *
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     *
     * @return int The Levenshtein distance between the two strings.
     */

    public static function levenDist ($string, $toString)
    {
        assert( 'is_cstring($string) && is_cstring($toString)', vs(isset($this), get_defined_vars()) );
        assert( 'strlen($string) <= 255 && strlen($toString) <= 255', vs(isset($this), get_defined_vars()) );

        return levenshtein($string, $toString);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the Metaphone key "heard" from a string.
     *
     * The algorithm used to render the [Metaphone](http://en.wikipedia.org/wiki/Metaphone) key is the first-generation
     * one.
     *
     * @param  string $string The source string.
     *
     * @return string The Metaphone key of the string.
     */

    public static function metaphoneKey ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );

        $res = metaphone($string);
        if ( is_cstring($res) )
        {
            return $res;
        }
        else
        {
            assert( 'false', vs(isset($this), get_defined_vars()) );
            return "";
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the Levenshtein distance calculated between the Metaphone keys of two strings.
     *
     * For any two strings, the [Levenshtein distance](http://en.wikipedia.org/wiki/Levenshtein_distance) is the total
     * number of insert, replace, and delete operations required to transform the first string into the second one. The
     * algorithm used to render the [Metaphone](http://en.wikipedia.org/wiki/Metaphone) keys is the first-generation
     * one.
     *
     * @param  string $string The first string for comparison.
     * @param  string $toString The second string for comparison.
     *
     * @return int The Levenshtein distance between the Metaphone keys of the two strings.
     */

    public static function metaphoneDist ($string, $toString)
    {
        assert( 'is_cstring($string) && is_cstring($toString)', vs(isset($this), get_defined_vars()) );
        return self::levenDist(self::metaphoneKey($string), self::metaphoneKey($toString));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts all the characters in a string to lowercase and returns the new string.
     *
     * @param  string $string The string to be converted.
     *
     * @return string The converted string.
     */

    public static function toLowerCase ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return strtolower($string);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts all the characters in a string to uppercase and returns the new string.
     *
     * @param  string $string The string to be converted.
     *
     * @return string The converted string.
     */

    public static function toUpperCase ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return strtoupper($string);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts the first character in a string to uppercase and returns the new string.
     *
     * @param  string $string The string to be converted.
     *
     * @return string The converted string.
     */

    public static function toUpperCaseFirst ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return ucfirst($string);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts the first character in each word that there is in a string to uppercase and returns the new string.
     *
     * @param  string $string The string to be converted.
     *
     * @return string The converted string.
     */

    public static function toTitleCase ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return ucwords($string);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string starts with a specified substring, searching case-sensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $withString The searched substring.
     *
     * @return bool `true` if the string starts with the substring specified, taking into account the letter case of
     * the characters, and `false` otherwise.
     */

    public static function startsWith ($string, $withString)
    {
        assert( 'is_cstring($string) && is_cstring($withString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($withString) )
        {
            return true;
        }
        return ( strpos($string, $withString) === 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string starts with a specified substring, searching case-insensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $withString The searched substring.
     *
     * @return bool `true` if the string starts with the substring specified, ignoring the letter case of the
     * characters, and `false` otherwise.
     */

    public static function startsWithCi ($string, $withString)
    {
        assert( 'is_cstring($string) && is_cstring($withString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($withString) )
        {
            return true;
        }
        return ( stripos($string, $withString) === 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string ends with a specified substring, searching case-sensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $withString The searched substring.
     *
     * @return bool `true` if the string ends with the substring specified, taking into account the letter case of the
     * characters, and `false` otherwise.
     */

    public static function endsWith ($string, $withString)
    {
        assert( 'is_cstring($string) && is_cstring($withString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($withString) )
        {
            return true;
        }
        return ( strrpos($string, $withString) === strlen($string) - strlen($withString) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string ends with a specified substring, searching case-insensitively.
     *
     * As a special case, the method returns `true` if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $withString The searched substring.
     *
     * @return bool `true` if the string ends with the substring specified, ignoring the letter case of the characters,
     * and `false` otherwise.
     */

    public static function endsWithCi ($string, $withString)
    {
        assert( 'is_cstring($string) && is_cstring($withString)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($withString) )
        {
            return true;
        }
        return ( strripos($string, $withString) === strlen($string) - strlen($withString) );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the first occurrence of a substring in a string, searching case-sensitively.
     *
     * As a special case, the method returns the starting position of the search if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $ofString The searched substring.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the first occurrence of the substring in the string or `-1` if no such substring was
     * found, taking into account the letter case during the search.
     */

    public static function indexOf ($string, $ofString, $startPos = 0)
    {
        assert( 'is_cstring($string) && is_cstring($ofString) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $startPos && $startPos <= strlen($string)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($ofString) )
        {
            return $startPos;
        }
        $res = strpos($string, $ofString, $startPos);
        return ( is_int($res) ) ? $res : -1;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the first occurrence of a substring in a string, searching case-insensitively.
     *
     * As a special case, the method returns the starting position of the search if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $ofString The searched substring.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the first occurrence of the substring in the string or `-1` if no such substring was
     * found, ignoring the letter case during the search.
     */

    public static function indexOfCi ($string, $ofString, $startPos = 0)
    {
        assert( 'is_cstring($string) && is_cstring($ofString) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $startPos && $startPos <= strlen($string)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($ofString) )
        {
            return $startPos;
        }
        $res = stripos($string, $ofString, $startPos);
        return ( is_int($res) ) ? $res : -1;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the last occurrence of a substring in a string, searching case-sensitively.
     *
     * As a special case, the method returns the string's length if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $ofString The searched substring.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the last occurrence of the substring in the string or `-1` if no such substring was
     * found, taking into account the letter case during the search.
     */

    public static function lastIndexOf ($string, $ofString, $startPos = 0)
    {
        assert( 'is_cstring($string) && is_cstring($ofString) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $startPos && $startPos <= strlen($string)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($ofString) )
        {
            return strlen($string);
        }
        $res = strrpos($string, $ofString, $startPos);
        return ( is_int($res) ) ? $res : -1;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns the position of the last occurrence of a substring in a string, searching case-insensitively.
     *
     * As a special case, the method returns the string's length if the searched substring is empty.
     *
     * @param  string $string The string to be looked into.
     * @param  string $ofString The searched substring.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The position of the last occurrence of the substring in the string or `-1` if no such substring was
     * found, ignoring the letter case during the search.
     */

    public static function lastIndexOfCi ($string, $ofString, $startPos = 0)
    {
        assert( 'is_cstring($string) && is_cstring($ofString) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $startPos && $startPos <= strlen($string)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($ofString) )
        {
            return strlen($string);
        }
        $res = strripos($string, $ofString, $startPos);
        return ( is_int($res) ) ? $res : -1;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified substring, searching case-sensitively.
     *
     * @param  string $string The string to be looked into.
     * @param  string $whatString The searched substring.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return bool `true` if the substring was found in the string, taking into account the letter case during the
     * search, and `false` otherwise.
     */

    public static function find ($string, $whatString, $startPos = 0)
    {
        assert( 'is_cstring($string) && is_cstring($whatString) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        return ( self::indexOf($string, $whatString, $startPos) != -1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if a string contains a specified substring, searching case-insensitively.
     *
     * @param  string $string The string to be looked into.
     * @param  string $whatString The searched substring.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return bool `true` if the substring was found in the string, ignoring the letter case during the search, and
     * `false` otherwise.
     */

    public static function findCi ($string, $whatString, $startPos = 0)
    {
        assert( 'is_cstring($string) && is_cstring($whatString) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        return ( self::indexOfCi($string, $whatString, $startPos) != -1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Determines if the characters in a string are a subset of the characters in another string.
     *
     * @param  string $string The string to be looked into.
     * @param  string $ofCharSet The reference string.
     *
     * @return bool `true` if the string is a subset of the reference string, `false` otherwise.
     */

    public static function isSubsetOf ($string, $ofCharSet)
    {
        assert( 'is_cstring($string) && is_cstring($ofCharSet)', vs(isset($this), get_defined_vars()) );

        if ( self::isEmpty($string) && !self::isEmpty($ofCharSet) )
        {
            // Special case.
            return false;
        }

        for ($i0 = 0; $i0 < strlen($string); $i0++)
        {
            $isIn = false;
            for ($i1 = 0; $i1 < strlen($ofCharSet); $i1++)
            {
                if ( self::equals($string[$i0], $ofCharSet[$i1]) )
                {
                    $isIn = true;
                    break;
                }
            }
            if ( !$isIn )
            {
                return false;
            }
        }
        return true;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a substring from a string.
     *
     * As a special case, the method returns an empty string if the starting position is equal to the string's length
     * or if the substring's length, if specified, is `0`.
     *
     * @param  string $string The string to be looked into.
     * @param  int $startPos The position of the substring's first character.
     * @param  int $length **OPTIONAL. Default is** *as many characters as the starting character is followed by*. The
     * length of the substring.
     *
     * @return string The substring.
     */

    public static function substr ($string, $startPos, $length = null)
    {
        assert( 'is_cstring($string) && is_int($startPos) && (!isset($length) || is_int($length))',
            vs(isset($this), get_defined_vars()) );
        assert( '(0 <= $startPos && $startPos < strlen($string)) || ($startPos == strlen($string) && ' .
            '(!isset($length) || $length == 0))', vs(isset($this), get_defined_vars()) );
        assert( '!isset($length) || ($length >= 0 && $startPos + $length <= strlen($string))',
            vs(isset($this), get_defined_vars()) );

        $res;
        if ( !isset($length) )
        {
            $res = substr($string, $startPos);
        }
        else
        {
            $res = substr($string, $startPos, $length);
        }
        return ( is_cstring($res) ) ? $res : "";
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Returns a substring from a string, with both starting and ending positions specified.
     *
     * As a special case, the method returns an empty string if the starting and ending positions are the same, with
     * the greatest such position being the string's length.
     *
     * @param  string $string The string to be looked into.
     * @param  int $startPos The position of the substring's first character.
     * @param  int $endPos The position of the character that *follows* the last character in the substring.
     *
     * @return string The substring.
     */

    public static function substring ($string, $startPos, $endPos)
    {
        assert( 'is_cstring($string) && is_int($startPos) && is_int($endPos)',
            vs(isset($this), get_defined_vars()) );
        return self::substr($string, $startPos, $endPos - $startPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Tells how many substrings with a specified text there are in a string.
     *
     * The search is case-sensitive.
     *
     * @param  string $string The string to be looked into.
     * @param  string $substring The searched substring.
     * @param  int $startPos **OPTIONAL. Default is** `0`. The starting position for the search.
     *
     * @return int The number of such substrings in the string.
     */

    public static function numSubstrings ($string, $substring, $startPos = 0)
    {
        assert( 'is_cstring($string) && is_cstring($substring) && is_int($startPos)',
            vs(isset($this), get_defined_vars()) );
        assert( '!self::isEmpty($substring)', vs(isset($this), get_defined_vars()) );
        assert( '(0 <= $startPos && $startPos < strlen($string)) || (self::isEmpty($string) && $startPos == 0)',
            vs(isset($this), get_defined_vars()) );

        return substr_count($string, $substring, $startPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Splits a string into substrings using a specified substring or substrings as the delimiter(s) and returns the
     * resulting strings as an array.
     *
     * If no delimiter substrings were found, the resulting array contains just one element, which is the original
     * string. If a delimiter is located at the very start or at the very end of the string or next to another
     * delimiter, it will accordingly cause some string(s) in the resulting array to be empty.
     *
     * As a special case, the delimiter substring can be empty, which will split the string into its constituting
     * characters.
     *
     * @param  string $string The string to be split.
     * @param  string|array|map $delimiterOrDelimiters The substring or array of substrings to be recognized as the
     * delimiter(s).
     *
     * @return CArray The resulting strings.
     */

    public static function split ($string, $delimiterOrDelimiters)
    {
        assert( 'is_cstring($string) && ' .
                '(is_cstring($delimiterOrDelimiters) || is_collection($delimiterOrDelimiters))',
            vs(isset($this), get_defined_vars()) );

        if ( is_cstring($delimiterOrDelimiters) )
        {
            if ( self::isEmpty($delimiterOrDelimiters) )
            {
                // Special case.
                if ( self::isEmpty($string) )
                {
                    $resStrings = CArray::fromElements("");
                    return $resStrings;
                }
                else
                {
                    $resStrings = CArray::make(strlen($string));
                    for ($i = 0; $i < strlen($string); $i++)
                    {
                        $resStrings[$i] = $string[$i];
                    }
                    return $resStrings;
                }
            }

            $resStrings = CArray::make(self::numSubstrings($string, $delimiterOrDelimiters) + 1);
            $startPos = 0;
            $i = 0;
            while ( true )
            {
                $endPos = self::indexOf($string, $delimiterOrDelimiters, $startPos);
                if ( $endPos != -1 )
                {
                    $resStrings[$i++] = self::substring($string, $startPos, $endPos);
                    $startPos = $endPos + strlen($delimiterOrDelimiters);
                }
                else
                {
                    $resStrings[$i] = self::substr($string, $startPos);
                    break;
                }
            }
            return $resStrings;
        }
        else  // a collection
        {
            $resStrings = CArray::fromElements($string);
            foreach ($delimiterOrDelimiters as $delimiter)
            {
                assert( 'is_cstring($delimiter)', vs(isset($this), get_defined_vars()) );
                $resStringsNew = CArray::make();
                $len = CArray::length($resStrings);
                for ($i = 0; $i < $len; $i++)
                {
                    CArray::pushArray($resStringsNew, self::split($resStrings[$i], $delimiter));
                }
                $resStrings = $resStringsNew;
            }
            return $resStrings;
        }
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Splits a string into its constituting characters.
     *
     * @param  string $string The string to be split.
     *
     * @return CArray The string's characters.
     */

    public static function splitIntoChars ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return self::split($string, "");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all characters from the start of a string that are non-printable, such as whitespace, or are invalid in
     * ASCII and returns the new string.
     *
     * @param  string $string The string to be trimmed.
     *
     * @return string The trimmed string.
     */

    public static function trimStart ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return preg_replace("/^(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+/", "", $string);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all characters from the end of a string that are non-printable, such as whitespace, or are invalid in
     * ASCII and returns the new string.
     *
     * @param  string $string The string to be trimmed.
     *
     * @return string The trimmed string.
     */

    public static function trimEnd ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );
        return preg_replace("/(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+\\z/", "", $string);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all characters from both ends of a string that are non-printable, such as whitespace, or are invalid in
     * ASCII and returns the new string.
     *
     * @param  string $string The string to be trimmed.
     *
     * @return string The trimmed string.
     */

    public static function trim ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );

        $string = self::trimStart($string);
        $string = self::trimEnd($string);
        return $string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Normalizes the spacing in a string by removing all characters from both of its ends that are non-printable, such
     * as whitespace, or are invalid in ASCII and replacing any sequence of such characters within the string with a
     * single space character, and returns the new string.
     *
     * @param  string $string The string to be normalized.
     *
     * @return string The normalized string.
     */

    public static function normSpacing ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );

        $string = preg_replace("/^(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+/", "", $string);
        $string = preg_replace("/(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+\\z/", "", $string);
        $string = preg_replace("/(" . self::TRIMMING_AND_SPACING_NORM_SUBJECT_RE . ")+/", " ", $string);
        return $string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Normalizes the newlines in a string by replacing any newline that is not an LF newline with an LF, which is the
     * standard newline format on Linux/Unix and OS X, or with a custom newline, and returns the new string.
     *
     * The known newline formats are LF (0x0A), CRLF (0x0D, 0x0A), CR (0x0D), VT (0x0B), and FF (0x0C).
     *
     * @param  string $string The string to be normalized.
     * @param  string $newline **OPTIONAL. Default is** LF (0x0A).
     *
     * @return string The normalized string.
     */

    public static function normNewlines ($string, $newline = self::NEWLINE)
    {
        assert( 'is_cstring($string) && is_cstring($newline)', vs(isset($this), get_defined_vars()) );
        return preg_replace("/" . self::NL_NORM_SUBJECT_RE . "/", $newline, $string);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds characters to the start of a string to make it grow to a specified length and returns the new string.
     *
     * If the input string is already of the targeted length, it's returned unmodified.
     *
     * @param  string $string The string to be padded.
     * @param  string $paddingString The string to be used for padding.
     * @param  int $newLength The length of the padded string.
     *
     * @return string The padded string.
     */

    public static function padStart ($string, $paddingString, $newLength)
    {
        assert( 'is_cstring($string) && is_cstring($paddingString) && is_int($newLength)',
            vs(isset($this), get_defined_vars()) );
        assert( '$newLength >= strlen($string)', vs(isset($this), get_defined_vars()) );

        return str_pad($string, $newLength, $paddingString, STR_PAD_LEFT);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Adds characters to the end of a string to make it grow to a specified length and returns the new string.
     *
     * If the input string is already of the targeted length, it's returned unmodified.
     *
     * @param  string $string The string to be padded.
     * @param  string $paddingString The string to be used for padding.
     * @param  int $newLength The length of the padded string.
     *
     * @return string The padded string.
     */

    public static function padEnd ($string, $paddingString, $newLength)
    {
        assert( 'is_cstring($string) && is_cstring($paddingString) && is_int($newLength)',
            vs(isset($this), get_defined_vars()) );
        assert( '$newLength >= strlen($string)', vs(isset($this), get_defined_vars()) );

        return str_pad($string, $newLength, $paddingString, STR_PAD_RIGHT);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the start of a string, if found searching case-sensitively, and
     * returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string $string The string to be stripped.
     * @param  string|array|map $prefixOrPrefixes The substring or array of substrings to be stripped off.
     *
     * @return string The stripped string.
     */

    public static function stripStart ($string, $prefixOrPrefixes)
    {
        assert( 'is_cstring($string) && (is_cstring($prefixOrPrefixes) || is_collection($prefixOrPrefixes))',
            vs(isset($this), get_defined_vars()) );

        if ( is_cstring($prefixOrPrefixes) )
        {
            if ( self::startsWith($string, $prefixOrPrefixes) )
            {
                $string = self::substr($string, strlen($prefixOrPrefixes));
            }
        }
        else  // a collection
        {
            foreach ($prefixOrPrefixes as $prefix)
            {
                assert( 'is_cstring($prefix)', vs(isset($this), get_defined_vars()) );
                if ( self::startsWith($string, $prefix) )
                {
                    $string = self::substr($string, strlen($prefix));
                }
            }
        }
        return $string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the start of a string, if found searching case-insensitively,
     * and returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string $string The string to be stripped.
     * @param  string|array|map $prefixOrPrefixes The substring or array of substrings to be stripped off.
     *
     * @return string The stripped string.
     */

    public static function stripStartCi ($string, $prefixOrPrefixes)
    {
        assert( 'is_cstring($string) && (is_cstring($prefixOrPrefixes) || is_collection($prefixOrPrefixes))',
            vs(isset($this), get_defined_vars()) );

        if ( is_cstring($prefixOrPrefixes) )
        {
            if ( self::startsWithCi($string, $prefixOrPrefixes) )
            {
                $string = self::substr($string, strlen($prefixOrPrefixes));
            }
        }
        else  // a collection
        {
            foreach ($prefixOrPrefixes as $prefix)
            {
                assert( 'is_cstring($prefix)', vs(isset($this), get_defined_vars()) );
                if ( self::startsWithCi($string, $prefix) )
                {
                    $string = self::substr($string, strlen($prefix));
                }
            }
        }
        return $string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the end of a string, if found searching case-sensitively, and
     * returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string $string The string to be stripped.
     * @param  string|array|map $suffixOrSuffixes The substring or array of substrings to be stripped off.
     *
     * @return string The stripped string.
     */

    public static function stripEnd ($string, $suffixOrSuffixes)
    {
        assert( 'is_cstring($string) && (is_cstring($suffixOrSuffixes) || is_collection($suffixOrSuffixes))',
            vs(isset($this), get_defined_vars()) );

        if ( is_cstring($suffixOrSuffixes) )
        {
            if ( self::endsWith($string, $suffixOrSuffixes) )
            {
                $string = self::substr($string, 0, strlen($string) - strlen($suffixOrSuffixes));
            }
        }
        else  // a collection
        {
            foreach ($suffixOrSuffixes as $suffix)
            {
                assert( 'is_cstring($suffix)', vs(isset($this), get_defined_vars()) );
                if ( self::endsWith($string, $suffix) )
                {
                    $string = self::substr($string, 0, strlen($string) - strlen($suffix));
                }
            }
        }
        return $string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a specified substring or substrings from the end of a string, if found searching case-insensitively, and
     * returns the new string.
     *
     * In case of multiple different substrings to be stripped off, the order of the substrings in the parameter array
     * does matter.
     *
     * @param  string $string The string to be stripped.
     * @param  string|array|map $suffixOrSuffixes The substring or array of substrings to be stripped off.
     *
     * @return string The stripped string.
     */

    public static function stripEndCi ($string, $suffixOrSuffixes)
    {
        assert( 'is_cstring($string) && (is_cstring($suffixOrSuffixes) || is_collection($suffixOrSuffixes))',
            vs(isset($this), get_defined_vars()) );

        if ( is_cstring($suffixOrSuffixes) )
        {
            if ( self::endsWithCi($string, $suffixOrSuffixes) )
            {
                $string = self::substr($string, 0, strlen($string) - strlen($suffixOrSuffixes));
            }
        }
        else  // a collection
        {
            foreach ($suffixOrSuffixes as $suffix)
            {
                assert( 'is_cstring($suffix)', vs(isset($this), get_defined_vars()) );
                if ( self::endsWithCi($string, $suffix) )
                {
                    $string = self::substr($string, 0, strlen($string) - strlen($suffix));
                }
            }
        }
        return $string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Inserts a string into another string and returns the new string.
     *
     * As a special case, the position of insertion can be equal to the target string's length.
     *
     * @param  string $string The target string.
     * @param  int $atPos The position of insertion. This is the desired position of the first character of the
     * inserted string in the resulting string.
     * @param  string $insertString The string to be inserted.
     *
     * @return string The resulting string.
     */

    public static function insert ($string, $atPos, $insertString)
    {
        assert( 'is_cstring($string) && is_int($atPos) && is_cstring($insertString)',
            vs(isset($this), get_defined_vars()) );
        assert( '0 <= $atPos && $atPos <= strlen($string)', vs(isset($this), get_defined_vars()) );

        return self::substr($string, 0, $atPos) . $insertString . self::substr($string, $atPos);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In a string, replaces a substring with a specified string, and returns the new string.
     *
     * @param  string $string The source string.
     * @param  int $startPos The position of the first character in the substring to be replaced.
     * @param  int $length The length of the substring to be replaced.
     * @param  string $with The replacement string.
     *
     * @return string The resulting string.
     */

    public static function replaceSubstring ($string, $startPos, $length, $with)
    {
        assert( 'is_cstring($string) && is_int($startPos) && is_int($length) && is_cstring($with)',
            vs(isset($this), get_defined_vars()) );
        assert( '(0 <= $startPos && $startPos < strlen($string)) || ' .
                '($startPos == strlen($string) && $length == 0)', vs(isset($this), get_defined_vars()) );
        assert( '$length >= 0 && $startPos + $length <= strlen($string)', vs(isset($this), get_defined_vars()) );

        return self::substr($string, 0, $startPos) . $with . self::substr($string, $startPos + $length);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * In a string, replaces a substring with a specified string, with both starting and ending positions specified,
     * and returns the new string.
     *
     * @param  string $string The source string.
     * @param  int $startPos The position of the first character in the substring to be replaced.
     * @param  int $endPos The position of the character that *follows* the last character in the substring to be
     * replaced.
     * @param  string $with The replacement string.
     *
     * @return string The resulting string.
     */

    public static function replaceSubstringByRange ($string, $startPos, $endPos, $with)
    {
        assert( 'is_cstring($string) && is_int($startPos) && is_int($endPos) && is_cstring($with)',
            vs(isset($this), get_defined_vars()) );
        return self::replaceSubstring($string, $startPos, $endPos - $startPos, $with);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a substring from a string and returns the new string.
     *
     * @param  string $string The source string.
     * @param  int $startPos The position of the first character in the substring to be removed.
     * @param  int $length The length of the substring to be removed.
     *
     * @return string The resulting string.
     */

    public static function removeSubstring ($string, $startPos, $length)
    {
        assert( 'is_cstring($string) && is_int($startPos) && is_int($length)',
            vs(isset($this), get_defined_vars()) );
        return self::replaceSubstring($string, $startPos, $length, "");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes a substring from a string, with both starting and ending positions specified, and returns the new
     * string.
     *
     * @param  string $string The source string.
     * @param  int $startPos The position of the first character in the substring to be removed.
     * @param  int $endPos The position of the character that *follows* the last character in the substring to be
     * removed.
     *
     * @return string The resulting string.
     */

    public static function removeSubstringByRange ($string, $startPos, $endPos)
    {
        assert( 'is_cstring($string) && is_int($startPos) && is_int($endPos)',
            vs(isset($this), get_defined_vars()) );
        return self::replaceSubstringByRange($string, $startPos, $endPos, "");
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces all occurrences of a substring in a string with a specified string, searching case-sensitively, and
     * returns the new string, optionally reporting the number of replacements made.
     *
     * @param  string $string The source string.
     * @param  string $what The searched substring.
     * @param  string $with The replacement string.
     * @param  reference $quantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of replacements made.
     *
     * @return string The resulting string.
     */

    public static function replace ($string, $what, $with, &$quantity = null)
    {
        assert( 'is_cstring($string) && is_cstring($what) && is_cstring($with)',
            vs(isset($this), get_defined_vars()) );

        return str_replace($what, $with, $string, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Replaces all occurrences of a substring in a string with a specified string, searching case-insensitively, and
     * returns the new string, optionally reporting the number of replacements made.
     *
     * @param  string $string The source string.
     * @param  string $what The searched substring.
     * @param  string $with The replacement string.
     * @param  reference $quantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of replacements made.
     *
     * @return string The resulting string.
     */

    public static function replaceCi ($string, $what, $with, &$quantity = null)
    {
        assert( 'is_cstring($string) && is_cstring($what) && is_cstring($with)',
            vs(isset($this), get_defined_vars()) );

        return str_ireplace($what, $with, $string, $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all occurrences of a substring from a string, searching case-sensitively, and returns the new string,
     * optionally reporting the number of removals made.
     *
     * @param  string $string The source string.
     * @param  string $what The searched substring.
     * @param  reference $quantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of removals made.
     *
     * @return string The resulting string.
     */

    public static function remove ($string, $what, &$quantity = null)
    {
        assert( 'is_cstring($string) && is_cstring($what)', vs(isset($this), get_defined_vars()) );
        return self::replace($string, $what, "", $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Removes all occurrences of a substring from a string, searching case-insensitively, and returns the new string,
     * optionally reporting the number of removals made.
     *
     * @param  string $string The source string.
     * @param  string $what The searched substring.
     * @param  reference $quantity **OPTIONAL. OUTPUT.** After the method is called with this parameter provided, the
     * parameter's value, which is of type `int`, indicates the number of removals made.
     *
     * @return string The resulting string.
     */

    public static function removeCi ($string, $what, &$quantity = null)
    {
        assert( 'is_cstring($string) && is_cstring($what)', vs(isset($this), get_defined_vars()) );
        return self::replaceCi($string, $what, "", $quantity);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Randomizes the positions of the characters in a string and returns the new string.
     *
     * @param  string $string The string to be shuffled.
     *
     * @return string The shuffled string.
     */

    public static function shuffle ($string)
    {
        assert( 'is_cstring($string)', vs(isset($this), get_defined_vars()) );

        // The Fisher-Yates algorithm.
        for ($i = strlen($string) - 1; $i > 0; $i--)
        {
            $exchangeIdx = CMathi::intervalRandom(0, $i);
            $save = $string[$exchangeIdx];
            $string[$exchangeIdx] = $string[$i];
            $string[$i] = $save;
        }
        return $string;
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Wraps the text in a string to a specified width and returns the new string.
     *
     * @param  string $string The string with the text to be wrapped.
     * @param  int $width The wrapping width, in characters.
     * @param  bool $breakSpacelessLines **OPTIONAL. Default is** `false`. Tells whether to break any line that
     * exceeds the wrapping width while doesn't contain any spaces at which it could be broken.
     * @param  string $newline **OPTIONAL. Default is** LF (0x0A). The newline character(s) to be used for making
     * new lines during the wrapping.
     *
     * @return string The wrapped text.
     */

    public static function wordWrap ($string, $width, $breakSpacelessLines = false, $newline = self::NEWLINE)
    {
        assert( 'is_cstring($string) && is_int($width) && is_bool($breakSpacelessLines) && is_cstring($newline)',
            vs(isset($this), get_defined_vars()) );
        assert( '$width > 0', vs(isset($this), get_defined_vars()) );

        return wordwrap($string, $width, $newline, $breakSpacelessLines);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a decimal integer into the corresponding hexadecimal integer and returns it as another
     * string.
     *
     * @param  string $number The string with the number to be converted.
     *
     * @return string The string with the converted number.
     */

    public static function decToHex ($number)
    {
        assert( 'is_cstring($number)', vs(isset($this), get_defined_vars()) );
        assert( 'preg_match("/^[0-9]+\\\\z/", $number) === 1', vs(isset($this), get_defined_vars()) );

        return strtoupper(dechex($number));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with a hexadecimal integer into the corresponding decimal integer and returns it as another
     * string.
     *
     * The input string may be prefixed with "0x".
     *
     * @param  string $number The string with the number to be converted.
     *
     * @return string The string with the converted number.
     */

    public static function hexToDec ($number)
    {
        assert( 'is_cstring($number)', vs(isset($this), get_defined_vars()) );
        assert( 'preg_match("/^(0x)?[0-9A-F]+\\\\z/i", $number) === 1', vs(isset($this), get_defined_vars()) );

        return (string)hexdec($number);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Converts a string with an arbitrary-base integer into the corresponding integer in a different base and returns
     * it as another string.
     *
     * The input string may be prefixed with "0x" for the source base of 16.
     *
     * @param  string $number The string with the number to be converted.
     * @param  int $fromBase The source base. Can be a number in the range from 2 to 36.
     * @param  int $toBase The destination base. Can be a number in the range from 2 to 36.
     *
     * @return string The string with the converted number.
     */

    public static function numberToBase ($number, $fromBase, $toBase)
    {
        assert( 'is_cstring($number) && is_int($fromBase) && is_int($toBase)',
            vs(isset($this), get_defined_vars()) );
        assert( '2 <= $fromBase && $fromBase <= 36', vs(isset($this), get_defined_vars()) );
        assert( '2 <= $toBase && $toBase <= 36', vs(isset($this), get_defined_vars()) );

        return strtoupper(base_convert($number, $fromBase, $toBase));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    /**
     * Repeats a string for a specified number of times and returns the resulting string.
     *
     * For instance, the string of "a" repeated three times would result in "aaa".
     *
     * @param  string $string The string to be repeated.
     * @param  int $times The number of times for the string to be repeated.
     *
     * @return string The resulting string.
     */

    public static function repeat ($string, $times)
    {
        assert( 'is_cstring($string) && is_int($times)', vs(isset($this), get_defined_vars()) );
        assert( '$times > 0 || (self::isEmpty($string) && $times == 0)', vs(isset($this), get_defined_vars()) );

        return str_repeat($string, $times);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}
