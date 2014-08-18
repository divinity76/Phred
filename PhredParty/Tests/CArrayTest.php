<?php

// Phred is providing PHP with a consistent, Unicode-enabled, and completely object-oriented coding standard.
// Copyright (c) 2013-2014 Nazariy Gorpynyuk
// Distributed under the GNU General Public License, Version 2.0
// https://www.gnu.org/licenses/gpl-2.0.txt

/**
 * @ignore
 */

class CArrayTest extends CTestCase
{
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMake ()
    {
        $array = CArray::make();
        $this->assertTrue( $array->getSize() == 0 );
        $array = CArray::make(10);
        $this->assertTrue( $array->getSize() == 10 );

        $array = CArray::makeDim2(2, 3);
        $this->assertTrue(
            $array->getSize() == 2 &&
            $array[0]->getSize() == 3 && $array[1]->getSize() == 3 );

        $array = CArray::makeDim3(2, 3, 2);
        $this->assertTrue(
            $array->getSize() == 2 &&
            $array[0]->getSize() == 3 && $array[1]->getSize() == 3 &&
            $array[0][0]->getSize() == 2 && $array[0][1]->getSize() == 2 && $array[0][2]->getSize() == 2 );

        $array = CArray::makeDim4(2, 3, 2, 1);
        $this->assertTrue(
            $array->getSize() == 2 &&
            $array[0]->getSize() == 3 && $array[1]->getSize() == 3 &&
            $array[0][0]->getSize() == 2 && $array[0][1]->getSize() == 2 && $array[0][2]->getSize() == 2 &&
            $array[0][0][0]->getSize() == 1 && $array[0][0][1]->getSize() == 1 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testMakeCopy ()
    {
        $array0 = CArray::make(3);
        $array0[0] = "a";
        $array0[1] = "b";
        $array0[2] = "c";
        $array1 = CArray::makeCopy($array0);
        $this->assertTrue(CArray::equals($array0, $array1));
        $array0[0] = "d";
        $this->assertTrue( $array1[0] === "a" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromPArray ()
    {
        $map = [0 => "a", 1 => "b", 3 => "c", 9 => "d"];
        $array = CArray::fromPArray($map);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c", "d")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFromElements ()
    {
        $array = CArray::fromElements("a", "b", "c");
        $this->assertTrue( $array[0] === "a" && $array[1] === "b" && $array[2] === "c" );

        $array = CArray::fe("a", "b", "c");
        $this->assertTrue( $array[0] === "a" && $array[1] === "b" && $array[2] === "c" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testToPArray ()
    {
        $array = CArray::fromElements("a", "b", "c");
        $map = CArray::toPArray($array);
        $this->assertTrue( $map[0] === "a" && $map[1] === "b" && $map[2] === "c" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testJoin ()
    {
        $array = CArray::fromElements("a", "b", "c");
        $joined = CArray::join($array, ", ");
        $this->assertTrue( $joined === "a, b, c" );

        $array = CArray::fromElements(true, false, true);
        $joined = CArray::join($array, ", ");
        $this->assertTrue( $joined === "1, 0, 1" );

        $array = CArray::fromElements(12, 34, 56);
        $joined = CArray::join($array, ", ");
        $this->assertTrue( $joined === "12, 34, 56" );

        $array = CArray::fromElements(1.2, 3.4, 5.6);
        $joined = CArray::join($array, ", ");
        $this->assertTrue( $joined === "1.2, 3.4, 5.6" );

        $array = CArray::fromElements("a", "b", "c");
        $joined = CArray::join($array, "");
        $this->assertTrue( $joined === "abc" );

        // Special cases.

        $array = CArray::make();
        $joined = CArray::join($array, "");
        $this->assertTrue( $joined === "" );

        $array = CArray::make();
        $joined = CArray::join($array, ", ");
        $this->assertTrue( $joined === "" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLength ()
    {
        $array = CArray::make();
        $this->assertTrue( CArray::length($array) == 0 );

        $array = CArray::make(2);
        $this->assertTrue( CArray::length($array) == 2 );

        $array = CArray::fromElements("a", "b", "c");
        $this->assertTrue( CArray::length($array) == 3 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsEmpty ()
    {
        $array = CArray::make();
        $this->assertTrue(CArray::isEmpty($array));

        $array = CArray::make(2);
        $this->assertFalse(CArray::isEmpty($array));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testEquals ()
    {
        // Using the default comparator.

        $array0 = CArray::fromElements("a", "b", "c");
        $array1 = CArray::fromElements("a", "b", "c");
        $this->assertTrue(CArray::equals($array0, $array1));

        $array0 = CArray::fromElements("a", "b", "c");
        $array1 = CArray::fromElements("a", "b", "d");
        $this->assertFalse(CArray::equals($array0, $array1));

        $array0 = CArray::fromElements("a", "b", "c");
        $array1 = CArray::fromElements("a", "b", "C");
        $this->assertFalse(CArray::equals($array0, $array1));

        $array0 = CArray::fromElements("a", "b", "c");
        $array1 = CArray::fromElements("a", "b", "c", "d");
        $this->assertFalse(CArray::equals($array0, $array1));

        $array0 = CArray::fromElements(1, 2, 3);
        $array1 = CArray::fromElements(1, 2, 3);
        $this->assertTrue(CArray::equals($array0, $array1));

        $array0 = CArray::fromElements(1.2, 3.4, 5.6);
        $array1 = CArray::fromElements(1.2, 3.4, 5.6);
        $this->assertTrue(CArray::equals($array0, $array1));

        $array0 = CArray::fromElements("a", "b", "c");
        $array1 = CArray::fromElements(u("a"), u("b"), u("c"));
        $this->assertTrue(CArray::equals($array0, $array1));

        $array0 = CArray::make();
        $array1 = CArray::fromElements("a", "b", "c");
        $this->assertFalse(CArray::equals($array0, $array1));

        $array0 = CArray::fromElements("a", "b", "c");
        $array1 = CArray::make();
        $this->assertFalse(CArray::equals($array0, $array1));

        // Using a custom comparator.
        $array0 = CArray::fromElements("a", "b", "c");
        $array1 = CArray::fromElements("A", "B", "C");
        $comparator = function ($string0, $string1)
            {
                return ( CString::toLowerCase($string0) === CString::toLowerCase($string1) );
            };
        $this->assertTrue(CArray::equals($array0, $array1, $comparator));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCompare ()
    {
        // Using the default comparator.

        $array0 = CArray::fromElements("a", "b", "c");
        $array1 = CArray::fromElements("a", "b", "c");
        $this->assertTrue( CArray::compare($array0, $array1) == 0 );

        $array0 = CArray::fromElements("a", "b", "c");
        $array1 = CArray::fromElements("d", "e", "f");
        $this->assertTrue( CArray::compare($array0, $array1) < 0 );

        $array0 = CArray::fromElements("d", "e", "f");
        $array1 = CArray::fromElements("a", "e", "f");
        $this->assertTrue( CArray::compare($array0, $array1) > 0 );

        $array0 = CArray::fromElements("a", "b");
        $array1 = CArray::fromElements("a", "b", "c");
        $this->assertTrue( CArray::compare($array0, $array1) < 0 );

        $array0 = CArray::fromElements(1, 2, 3);
        $array1 = CArray::fromElements(1, 2, 3);
        $this->assertTrue( CArray::compare($array0, $array1) == 0 );

        $array0 = CArray::fromElements(1, 2, 3);
        $array1 = CArray::fromElements(4, 5, 6);
        $this->assertTrue( CArray::compare($array0, $array1) < 0 );

        $array0 = CArray::fromElements(4, 5, 6);
        $array1 = CArray::fromElements(3, 5, 6);
        $this->assertTrue( CArray::compare($array0, $array1) > 0 );

        $array0 = CArray::fromElements(1.2, 3.4, 5.6);
        $array1 = CArray::fromElements(1.2, 3.4, 5.6);
        $this->assertTrue( CArray::compare($array0, $array1) == 0 );

        $array0 = CArray::fromElements("a", "b", "c");
        $array1 = CArray::fromElements(u("a"), u("b"), u("c"));
        $this->assertTrue( CArray::compare($array0, $array1) == 0 );

        // Using a custom comparator.

        $array0 = CArray::fromElements("a", "b", "c");
        $array1 = CArray::fromElements("A", "B", "C");
        $comparator = function ($string0, $string1)
            {
                return ( CString::toLowerCase($string0) === CString::toLowerCase($string1) ) ? 0 : -1;
            };
        $this->assertTrue( CArray::compare($array0, $array1, $comparator) == 0 );

        $array0 = CArray::fromElements(1, 3, 5);
        $array1 = CArray::fromElements(2, 4, 6);
        $comparator = function ($value0, $value1)
            {
                return ( CMathi::isOdd($value0) && CMathi::isEven($value1) ) ? 1 : -1;
            };
        $this->assertTrue( CArray::compare($array0, $array1, $comparator) > 0 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFirst ()
    {
        $array = CArray::fromElements("a", "b", "c");
        $this->assertTrue( CArray::first($array) === "a" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testLast ()
    {
        $array = CArray::fromElements("a", "b", "c");
        $this->assertTrue( CArray::last($array) === "c" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSubar ()
    {
        $array = CArray::fromElements("a", "b", "c", "d", "e");

        $subarray = CArray::subar($array, 3);
        $this->assertTrue(CArray::equals($subarray, CArray::fromElements("d", "e")));

        $subarray = CArray::subar($array, 1, 3);
        $this->assertTrue(CArray::equals($subarray, CArray::fromElements("b", "c", "d")));

        // Special cases.

        $subarray = CArray::subar($array, 5);
        $this->assertTrue($subarray->getSize() == 0);

        $subarray = CArray::subar($array, 5, 0);
        $this->assertTrue($subarray->getSize() == 0);

        $subarray = CArray::subar($array, 0, 0);
        $this->assertTrue($subarray->getSize() == 0);

        $subarray = CArray::subar($array, 2, 0);
        $this->assertTrue($subarray->getSize() == 0);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSubarray ()
    {
        $array = CArray::fromElements("a", "b", "c", "d", "e");

        $subarray = CArray::subarray($array, 1, 4);
        $this->assertTrue(CArray::equals($subarray, CArray::fromElements("b", "c", "d")));

        $subarray = CArray::subarray($array, 3, 5);
        $this->assertTrue(CArray::equals($subarray, CArray::fromElements("d", "e")));

        // Special cases.

        $subarray = CArray::subarray($array, 5, 5);
        $this->assertTrue($subarray->getSize() == 0);

        $subarray = CArray::subarray($array, 0, 0);
        $this->assertTrue($subarray->getSize() == 0);

        $subarray = CArray::subarray($array, 2, 2);
        $this->assertTrue($subarray->getSize() == 0);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSlice ()
    {
        $array = CArray::fromElements("a", "b", "c", "d", "e");

        $subarray = CArray::slice($array, 1, 4);
        $this->assertTrue(CArray::equals($subarray, CArray::fromElements("b", "c", "d")));

        $subarray = CArray::slice($array, 3, 5);
        $this->assertTrue(CArray::equals($subarray, CArray::fromElements("d", "e")));

        // Special cases.

        $subarray = CArray::slice($array, 5, 5);
        $this->assertTrue($subarray->getSize() == 0);

        $subarray = CArray::slice($array, 0, 0);
        $this->assertTrue($subarray->getSize() == 0);

        $subarray = CArray::slice($array, 2, 2);
        $this->assertTrue($subarray->getSize() == 0);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFind ()
    {
        $array = CArray::fromElements("a", "b", "c", "d", "e");

        // Using the default comparator.

        $found = CArray::find($array, "c");
        $this->assertTrue($found);

        $foundAtPos;
        $found = CArray::find($array, "d", CComparator::EQUALITY, $foundAtPos);
        $this->assertTrue($found);
        $this->assertTrue( $foundAtPos == 3 );

        $found = CArray::find($array, "C");
        $this->assertFalse($found);

        $found = CArray::find($array, "f");
        $this->assertFalse($found);

        // Using a custom comparator.
        $comparator = function ($string0, $string1)
            {
                return ( CString::toLowerCase($string0) === CString::toLowerCase($string1) );
            };
        $foundAtPos;
        $found = CArray::find($array, "C", $comparator, $foundAtPos);
        $this->assertTrue($found);
        $this->assertTrue( $foundAtPos == 2 );

        // Special case.
        $array = CArray::make();
        $found = CArray::find($array, "a");
        $this->assertFalse($found);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindScalar ()
    {
        $array = CArray::fromElements("a", "b", "c", "d", "e");

        $found = CArray::findScalar($array, "c");
        $this->assertTrue($found);

        $foundAtPos;
        $found = CArray::findScalar($array, "d", $foundAtPos);
        $this->assertTrue($found);
        $this->assertTrue( $foundAtPos == 3 );

        $found = CArray::findScalar($array, "C");
        $this->assertFalse($found);

        $found = CArray::findScalar($array, "f");
        $this->assertFalse($found);

        // Special case.
        $array = CArray::make();
        $found = CArray::findScalar($array, "a");
        $this->assertFalse($found);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFindBinary ()
    {
        $array = CArray::fromElements("oua", "vnf", "fnf", "aod", "tvi", "nbt", "jny", "vor", "rfd", "cvm", "hyh",
            "kng", "ggo", "uea", "hkb", "qbk", "xla", "uod", "jzi", "chw", "ssy", "olr", "bzl", "oux", "ltk", "bah",
            "khu", "msr", "pqv", "npb", "mtb", "eku", "vcv", "vbv", "wuo", "lrw", "bkw", "ezz", "jtc", "dwk", "dsq",
            "kzu", "oey", "vbi", "seh", "klz", "asj", "gzg", "ccs", "qop");
        $arrayOrig = CArray::makeCopy($array);
        $len = CArray::length($arrayOrig);

        // Sort the array first.
        CArray::sort($array, CComparator::ORDER_ASC);

        // Using the default comparators.
        for ($i = 0; $i < $len; $i += 3)
        {
            $string = $arrayOrig[$i];

            $foundAtPos0;
            CArray::find($array, $string, CComparator::EQUALITY, $foundAtPos0);
            $foundAtPos1;
            $found = CArray::findBinary($array, $string, CComparator::ORDER_ASC, $foundAtPos1);
            $this->assertTrue($found);
            $this->assertTrue( $foundAtPos1 == $foundAtPos0 );
        }

        // Using custom comparators.
        $comparatorEquality = function ($string0, $string1)
            {
                return ( CString::toLowerCase($string0) === CString::toLowerCase($string1) );
            };
        $comparatorOrderAsc = function ($string0, $string1)
            {
                return CString::compare(CString::toLowerCase($string0), CString::toLowerCase($string1));
            };
        for ($i = 0; $i < $len; $i += 5)
        {
            $string = CString::toUpperCase($arrayOrig[$i]);

            $foundAtPos0;
            CArray::find($array, $string, $comparatorEquality, $foundAtPos0);
            $foundAtPos1;
            $found = CArray::findBinary($array, $string, $comparatorOrderAsc, $foundAtPos1);
            $this->assertTrue($found);
            $this->assertTrue( $foundAtPos1 == $foundAtPos0 );
        }

        // Special cases.

        $array = CArray::fromElements("a", "b");
        $found = CArray::findBinary($array, "a");
        $this->assertTrue($found);
        $found = CArray::findBinary($array, "b");
        $this->assertTrue($found);
        $found = CArray::findBinary($array, "c");
        $this->assertFalse($found);

        $array = CArray::fromElements("a");
        $found = CArray::findBinary($array, "a");
        $this->assertTrue($found);

        $array = CArray::make();
        $found = CArray::findBinary($array, "a");
        $this->assertFalse($found);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testCountElement ()
    {
        $array = CArray::fromElements("a", "c", "b", "c", "d", "e", "c", "c", "f", "g", "h", "c");

        // Using the default comparator.
        $this->assertTrue( CArray::countElement($array, "c") == 5 );

        // Using a custom comparator.
        $comparator = function ($string0, $string1)
            {
                return ( CString::toLowerCase($string0) === CString::toLowerCase($string1) );
            };
        $this->assertTrue( CArray::countElement($array, "C", $comparator) == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSetLength ()
    {
        $array = CArray::fromElements("a", "b");
        CArray::setLength($array, 3);
        $this->assertTrue( $array->getSize() == 3 && $array[0] === "a" && $array[1] === "b" );
        CArray::setLength($array, 1);
        $this->assertTrue( $array->getSize() == 1 && $array[0] === "a" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPush ()
    {
        $array = CArray::fromElements("a", "b", "c");
        $newLength = CArray::push($array, "d");
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c", "d")));
        $this->assertTrue( $newLength == 4 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPop ()
    {
        $array = CArray::fromElements("a", "b", "c");
        $poppedString = CArray::pop($array);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b")));
        $this->assertTrue( $poppedString === "c" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPushArray ()
    {
        $array = CArray::fromElements("a", "b", "c");
        $newLength = CArray::pushArray($array, CArray::fromElements("d", "e"));
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c", "d", "e")));
        $this->assertTrue( $newLength == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnshift ()
    {
        $array = CArray::fromElements("a", "b", "c");
        $newLength = CArray::unshift($array, "d");
        $this->assertTrue(CArray::equals($array, CArray::fromElements("d", "a", "b", "c")));
        $this->assertTrue( $newLength == 4 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShift ()
    {
        $array = CArray::fromElements("a", "b", "c");
        $poppedString = CArray::shift($array);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("b", "c")));
        $this->assertTrue( $poppedString === "a" );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnshiftArray ()
    {
        $array = CArray::fromElements("a", "b", "c");
        $newLength = CArray::unshiftArray($array, CArray::fromElements("d", "e"));
        $this->assertTrue(CArray::equals($array, CArray::fromElements("d", "e", "a", "b", "c")));
        $this->assertTrue( $newLength == 5 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testInsert ()
    {
        $array = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::insert($array, 3, "x");
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c", "x", "d", "e")));

        $array = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::insert($array, 0, "x");
        $this->assertTrue(CArray::equals($array, CArray::fromElements("x", "a", "b", "c", "d", "e")));

        // Special cases.

        $array = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::insert($array, 5, "x");
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c", "d", "e", "x")));

        $array = CArray::make();
        CArray::insert($array, 0, "x");
        $this->assertTrue(CArray::equals($array, CArray::fromElements("x")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testInsertArray ()
    {
        $array = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::insertArray($array, 3, CArray::fromElements("x", "y"));
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c", "x", "y", "d", "e")));

        $array = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::insertArray($array, 0, CArray::fromElements("x", "y"));
        $this->assertTrue(CArray::equals($array, CArray::fromElements("x", "y", "a", "b", "c", "d", "e")));

        // Special cases.

        $array = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::insertArray($array, 5, CArray::fromElements("x", "y"));
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c", "d", "e", "x", "y")));

        $array = CArray::make();
        CArray::insertArray($array, 0, CArray::fromElements("x", "y"));
        $this->assertTrue(CArray::equals($array, CArray::fromElements("x", "y")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPadStart ()
    {
        $array = CArray::fromElements("a", "b", "c");
        CArray::padStart($array, " ", 5);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(" ", " ", "a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testPadEnd ()
    {
        $array = CArray::fromElements("a", "b", "c");
        CArray::padEnd($array, " ", 5);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c", " ", " ")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemove ()
    {
        $array = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::remove($array, 2);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "d", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveByValue ()
    {
        // Using the default comparator.

        $array = CArray::fromElements("a", "b", "c", "d", "e");
        $anyRemoval = CArray::removeByValue($array, "d");
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c", "e")));
        $this->assertTrue($anyRemoval);

        $array = CArray::fromElements("a", "b", "c", "d", "e", "c", "d", "e", "c", "d", "e");
        $anyRemoval = CArray::removeByValue($array, "d");
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c", "e", "c", "e", "c", "e")));
        $this->assertTrue($anyRemoval);

        $array = CArray::fromElements("a", "b", "c");
        $anyRemoval = CArray::removeByValue($array, "d");
        $this->assertFalse($anyRemoval);

        // Using a custom comparator.
        $comparator = function ($string0, $string1)
            {
                return ( CString::toLowerCase($string0) === CString::toLowerCase($string1) );
            };
        $array = CArray::fromElements("a", "b", "c", "d", "e");
        $anyRemoval = CArray::removeByValue($array, "D", $comparator);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c", "e")));
        $this->assertTrue($anyRemoval);
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveSubarray ()
    {
        $array = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::removeSubarray($array, 3);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c")));

        $array = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::removeSubarray($array, 1, 3);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "e")));

        // Special cases.

        $array = CArray::fromElements("a", "b", "c");
        CArray::removeSubarray($array, 3);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c")));

        $array = CArray::fromElements("a", "b", "c");
        CArray::removeSubarray($array, 3, 0);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c")));

        $array = CArray::fromElements("a", "b", "c");
        CArray::removeSubarray($array, 0, 0);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c")));

        $array = CArray::fromElements("a", "b", "c");
        CArray::removeSubarray($array, 1, 0);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSplice ()
    {
        $array = CArray::fromElements("a", "b", "c", "d", "e");
        $remArray = CArray::splice($array, 3);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c")));
        $this->assertTrue(CArray::equals($remArray, CArray::fromElements("d", "e")));

        $array = CArray::fromElements("a", "b", "c", "d", "e");
        $remArray = CArray::splice($array, 1, 3);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "e")));
        $this->assertTrue(CArray::equals($remArray, CArray::fromElements("b", "c", "d")));

        // Special cases.

        $array = CArray::fromElements("a", "b", "c");
        $remArray = CArray::splice($array, 3);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c")));
        $this->assertTrue(CArray::equals($remArray, CArray::make()));

        $array = CArray::fromElements("a", "b", "c");
        $remArray = CArray::splice($array, 3, 0);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c")));
        $this->assertTrue(CArray::equals($remArray, CArray::make()));

        $array = CArray::fromElements("a", "b", "c");
        $remArray = CArray::splice($array, 0, 0);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c")));
        $this->assertTrue(CArray::equals($remArray, CArray::make()));

        $array = CArray::fromElements("a", "b", "c");
        $remArray = CArray::splice($array, 1, 0);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c")));
        $this->assertTrue(CArray::equals($remArray, CArray::make()));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRemoveSubarrayByRange ()
    {
        $array = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::removeSubarrayByRange($array, 3, 5);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c")));

        $array = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::removeSubarrayByRange($array, 1, 4);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "e")));

        // Special cases.

        $array = CArray::fromElements("a", "b", "c");
        CArray::removeSubarrayByRange($array, 3, 3);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c")));

        $array = CArray::fromElements("a", "b", "c");
        CArray::removeSubarrayByRange($array, 0, 0);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c")));

        $array = CArray::fromElements("a", "b", "c");
        CArray::removeSubarrayByRange($array, 1, 1);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testReverse ()
    {
        $array = CArray::fromElements("a", "b", "c", "d", "e");
        CArray::reverse($array);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("e", "d", "c", "b", "a")));

        $array = CArray::fromElements("a", "b", "c", "d", "e", "f");
        CArray::reverse($array);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("f", "e", "d", "c", "b", "a")));

        // Special cases.

        $array = CArray::fromElements("a");
        CArray::reverse($array);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a")));

        $array = CArray::make();
        CArray::reverse($array);
        $this->assertTrue(CArray::equals($array, CArray::make()));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testShuffle ()
    {
        $array = CArray::fromElements("a", "b", "c", "d", "e", "f", "g", "h", "i");
        $arrayOrig = CArray::makeCopy($array);
        CArray::shuffle($array);
        $this->assertTrue(CArray::isSubsetOf($array, $arrayOrig));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSort ()
    {
        $array = CArray::fromElements("oua", "vnf", "fnf", "aod", "tvi", "nbt", "jny", "vor", "rfd", "cvm", "hyh",
            "kng", "ggo", "uea", "hkb", "qbk", "xla", "uod", "jzi", "chw", "ssy", "olr", "bzl", "oux", "ltk", "bah",
            "khu", "msr", "pqv", "npb", "mtb", "eku", "vcv", "vbv", "wuo", "lrw", "bkw", "ezz", "jtc", "dwk", "dsq",
            "kzu", "oey", "vbi", "seh", "klz", "asj", "gzg", "ccs", "qop");

        CArray::sort($array, CComparator::ORDER_ASC);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("aod", "asj", "bah", "bkw", "bzl", "ccs", "chw",
            "cvm", "dsq", "dwk", "eku", "ezz", "fnf", "ggo", "gzg", "hkb", "hyh", "jny", "jtc", "jzi", "khu", "klz",
            "kng", "kzu", "lrw", "ltk", "msr", "mtb", "nbt", "npb", "oey", "olr", "oua", "oux", "pqv", "qbk", "qop",
            "rfd", "seh", "ssy", "tvi", "uea", "uod", "vbi", "vbv", "vcv", "vnf", "vor", "wuo", "xla")));

        CArray::sort($array, CComparator::ORDER_DESC);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("xla", "wuo", "vor", "vnf", "vcv", "vbv", "vbi",
            "uod", "uea", "tvi", "ssy", "seh", "rfd", "qop", "qbk", "pqv", "oux", "oua", "olr", "oey", "npb", "nbt",
            "mtb", "msr", "ltk", "lrw", "kzu", "kng", "klz", "khu", "jzi", "jtc", "jny", "hyh", "hkb", "gzg", "ggo",
            "fnf", "ezz", "eku", "dwk", "dsq", "cvm", "chw", "ccs", "bzl", "bkw", "bah", "asj", "aod")));

        $array = CArray::fromElements(5, 2, 1, 3, 4);
        CArray::sort($array, CComparator::ORDER_ASC);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(1, 2, 3, 4, 5)));

        // Special cases.

        $array = CArray::fromElements("a");
        CArray::sort($array, CComparator::ORDER_ASC);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a")));

        $array = CArray::make();
        CArray::sort($array, CComparator::ORDER_ASC);
        $this->assertTrue(CArray::equals($array, CArray::make()));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortOn ()
    {
        $array = CArray::fromElements(
            new ClassForSorting("d"),
            new ClassForSorting("e"),
            new ClassForSorting("a"),
            new ClassForSorting("c"),
            new ClassForSorting("b"));
        CArray::sortOn($array, "value", CComparator::ORDER_ASC);
        $this->assertTrue(
            $array[0]->value() === "a" &&
            $array[1]->value() === "b" &&
            $array[2]->value() === "c" &&
            $array[3]->value() === "d" &&
            $array[4]->value() === "e" );

        $array = CArray::fromElements(
            new ClassForSorting(5),
            new ClassForSorting(2),
            new ClassForSorting(1),
            new ClassForSorting(3),
            new ClassForSorting(4));
        CArray::sortOn($array, "value", CComparator::ORDER_ASC);
        $this->assertTrue(
            $array[0]->value() == 1 &&
            $array[1]->value() == 2 &&
            $array[2]->value() == 3 &&
            $array[3]->value() == 4 &&
            $array[4]->value() == 5 );

        $array = CArray::fromElements(
            new ClassForSorting(u("d")),
            new ClassForSorting(u("e")),
            new ClassForSorting(u("a")),
            new ClassForSorting(u("c")),
            new ClassForSorting(u("b")));
        CArray::sortOn($array, "value", CComparator::ORDER_ASC);
        $this->assertTrue(
            CUString::equals($array[0]->value(), "a") &&
            CUString::equals($array[1]->value(), "b") &&
            CUString::equals($array[2]->value(), "c") &&
            CUString::equals($array[3]->value(), "d") &&
            CUString::equals($array[4]->value(), "e") );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortStrings ()
    {
        $array = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        CArray::sortStrings($array);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "A", "B", "C", "D", "E", "a", "b", "c", "d", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortStringsCi ()
    {
        $array = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        CArray::sortStringsCi($array);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "a", "A", "B", "b", "C", "c", "D", "d", "E", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortStringsNat ()
    {
        $array = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A100", "A20", "A3");
        CArray::sortStringsNat($array);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "A3", "A20", "A100", "B", "C", "D", "E", "a", "b", "c", "d", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortStringsNatCi ()
    {
        $array = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A100", "A20", "A3");
        CArray::sortStringsNatCi($array);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "a", "A3", "A20", "A100", "b", "B", "C", "c", "d", "D", "e", "E")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStrings ()
    {
        $array = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        CArray::sortUStrings($array, CUString::COLLATION_DEFAULT);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "a", "A", "b", "B", "c", "C", "d", "D", "e", "E")));

        $array = CArray::fromElements(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A");
        CArray::sortUStrings($array, CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "á", "A", "b", "B", "č", "C", "d", "D", "ê", "E")));

        $array = CArray::fromElements(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A");
        CArray::sortUStrings($array, CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "?a", "[A", "(b", ",B", " c", "!C", ".d", ";D", "\"e", ":E")));

        $array = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        CArray::sortUStrings($array, CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "A", "a", "B", "b", "C", "c", "D", "d", "E", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStringsCi ()
    {
        $array = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        CArray::sortUStringsCi($array, CUString::COLLATION_DEFAULT);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "a", "A", "B", "b", "C", "c", "D", "d", "E", "e")));

        $array = CArray::fromElements(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A");
        CArray::sortUStringsCi($array, CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "á", "A", "B", "b", "C", "č", "D", "d", "E", "ê")));

        $array = CArray::fromElements(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A");
        CArray::sortUStringsCi($array, CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "?a", "[A", ",B", "(b", "!C", " c", ";D", ".d", ":E", "\"e")));

        $array = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A");
        CArray::sortUStringsCi($array, CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "a", "A", "B", "b", "C", "c", "D", "d", "E", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStringsNat ()
    {
        $array = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        CArray::sortUStringsNat($array, CUString::COLLATION_DEFAULT);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "a", "A3", "A20", "A100", "b", "B", "c", "C", "d", "D", "e", "E")));

        $array = CArray::fromElements(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A3", "A20", "A100");
        CArray::sortUStringsNat($array, CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "á", "A3", "A20", "A100", "b", "B", "č", "C", "d", "D", "ê", "E")));

        $array = CArray::fromElements(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A3", "[A20", "[A100");
        CArray::sortUStringsNat($array, CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "?a", "[A3", "[A20", "[A100", "(b", ",B", " c", "!C", ".d", ";D", "\"e", ":E")));

        $array = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        CArray::sortUStringsNat($array, CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "a", "A3", "A20", "A100", "B", "b", "C", "c", "D", "d", "E", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSortUStringsNatCi ()
    {
        $array = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        CArray::sortUStringsNatCi($array, CUString::COLLATION_DEFAULT);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "a", "A3", "A20", "A100", "b", "B", "C", "c", "d", "D", "e", "E")));

        $array = CArray::fromElements(
            "č", "B", "d", "E", "D", "C", "á", "ê", "b", "A3", "A20", "A100");
        CArray::sortUStringsNatCi($array, CUString::COLLATION_IGNORE_ACCENTS);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "á", "A3", "A20", "A100", "b", "B", "C", "č", "d", "D", "ê", "E")));

        $array = CArray::fromElements(
            " c", ",B", ".d", ":E", ";D", "!C", "?a", "\"e", "(b", "[A3", "[A20", "[A100");
        CArray::sortUStringsNatCi($array, CUString::COLLATION_IGNORE_NONWORD);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "?a", "[A3", "[A20", "[A100", "(b", ",B", "!C", " c", ".d", ";D", "\"e", ":E")));

        $array = CArray::fromElements(
            "c", "B", "d", "E", "D", "C", "a", "e", "b", "A3", "A20", "A100");
        CArray::sortUStringsNatCi($array, CUString::COLLATION_UPPERCASE_FIRST);
        $this->assertTrue(CArray::equals($array, CArray::fromElements(
            "a", "A3", "A20", "A100", "b", "B", "C", "c", "d", "D", "e", "E")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testFilter ()
    {
        $array = CArray::fromElements(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        $array = CArray::filter($array, function ($element)
            {
                return CMathi::isEven($element);
            });
        $this->assertTrue(CArray::equals($array, CArray::fromElements(2, 4, 6, 8, 10)));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnique ()
    {
        // Using the default comparator.
        $array = CArray::fromElements("a", "b", "c", "d", "e", "a", "c", "e");
        $array = CArray::unique($array);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c", "d", "e")));

        // Using a custom comparator.
        $array = CArray::fromElements("A", "b", "C", "d", "E", "a", "c", "e");
        $comparator = function ($string0, $string1)
            {
                return ( CString::toLowerCase($string0) === CString::toLowerCase($string1) );
            };
        $array = CArray::unique($array, $comparator);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("A", "b", "C", "d", "E")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testElementsSum ()
    {
        $array = CArray::fromElements(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        $this->assertTrue( CArray::elementsSum($array) == 55 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testElementsProduct ()
    {
        $array = CArray::fromElements(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        $this->assertTrue( CArray::elementsProduct($array) == 3628800 );
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIsSubsetOf ()
    {
        // Using the default comparator.

        $array = CArray::fromElements("a", "b", "c", "a", "b", "c", "a", "b", "c");
        $this->assertTrue(CArray::isSubsetOf($array, CArray::fromElements("a", "b", "c")));

        $array = CArray::fromElements("a", "b", "c", "a", "b", "c", "a", "d", "b", "c");
        $this->assertFalse(CArray::isSubsetOf($array, CArray::fromElements("a", "b", "c")));

        // Using a custom comparator.
        $array = CArray::fromElements("a", "b", "c", "a", "b", "c", "a", "b", "c");
        $comparator = function ($string0, $string1)
            {
                return ( CString::toLowerCase($string0) === CString::toLowerCase($string1) );
            };
        $this->assertTrue(CArray::isSubsetOf($array, CArray::fromElements("A", "B", "C"), $comparator));

        // Special case.
        $array = CArray::make();
        $this->assertFalse(CArray::isSubsetOf($array, CArray::fromElements("a", "b", "c")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testUnion ()
    {
        $array0 = CArray::fromElements("a", "b", "c");
        $array1 = CArray::fromElements("d", "e", "f");
        $array2 = CArray::fromElements("g", "h", "i");
        $array = CArray::union($array0, $array1, $array2);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "b", "c", "d", "e", "f", "g", "h", "i")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testIntersection ()
    {
        // Using the default comparator.
        $array0 = CArray::fromElements("a", "b", "c", "d", "e", "f");
        $array1 = CArray::fromElements("g", "b", "h", "d", "i", "f");
        $array = CArray::intersection($array0, $array1);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("b", "d", "f")));

        // Using a custom comparator.
        $array0 = CArray::fromElements("a", "b", "c", "d", "e", "f");
        $array1 = CArray::fromElements("G", "B", "H", "D", "I", "F");
        $comparator = function ($string0, $string1)
            {
                return ( CString::toLowerCase($string0) === CString::toLowerCase($string1) );
            };
        $array = CArray::intersection($array0, $array1, $comparator);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("b", "d", "f")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testDifference ()
    {
        // Using the default comparator.
        $array0 = CArray::fromElements("a", "b", "c", "d", "e", "f");
        $array1 = CArray::fromElements("g", "b", "h", "d", "i", "f");
        $array = CArray::difference($array0, $array1);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "c", "e")));

        // Using a custom comparator.
        $array0 = CArray::fromElements("a", "b", "c", "d", "e", "f");
        $array1 = CArray::fromElements("G", "B", "H", "D", "I", "F");
        $comparator = function ($string0, $string1)
            {
                return ( CString::toLowerCase($string0) === CString::toLowerCase($string1) );
            };
        $array = CArray::difference($array0, $array1, $comparator);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "c", "e")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testSymmetricDifference ()
    {
        // Using the default comparator.
        $array0 = CArray::fromElements("a", "b", "c", "d", "e", "f");
        $array1 = CArray::fromElements("g", "b", "h", "d", "i", "f");
        $array = CArray::symmetricDifference($array0, $array1);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "c", "e", "g", "h", "i")));

        // Using a custom comparator.
        $array0 = CArray::fromElements("a", "b", "c", "d", "e", "f");
        $array1 = CArray::fromElements("G", "B", "H", "D", "I", "F");
        $comparator = function ($string0, $string1)
            {
                return ( CString::toLowerCase($string0) === CString::toLowerCase($string1) );
            };
        $array = CArray::symmetricDifference($array0, $array1, $comparator);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "c", "e", "G", "H", "I")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    public function testRepeat ()
    {
        $array = CArray::repeat("a", 5);
        $this->assertTrue(CArray::equals($array, CArray::fromElements("a", "a", "a", "a", "a")));
    }
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
}

/**
 * @ignore
 */

class ClassForSorting
{
    public function __construct ($sortOnValue)
    {
        $this->m_sortOnValue = $sortOnValue;
    }
    public function value ()
    {
        return $this->m_sortOnValue;
    }
    protected $m_sortOnValue;
}
