<?php
declare(strict_types=1);

namespace App\Tests;

use App\Day2;
use PHPUnit\Framework\TestCase;

final class Day2Test extends TestCase
{


//    abcdef contains no letters that appear exactly two or three times.
//    bababc contains two a and three b, so it counts for both.
//    abbcde contains two b, but no letter appears exactly three times.
//    abcccd contains three c, but no letter appears exactly two times.

//    aabcdd contains two a and two d, but it only counts once.
//    abcdee contains two e.
//    ababab contains three a and three b, but it only counts once.
//
//    Of these box IDs, four of them contain a letter which appears exactly twice,
//    and three of them contain a letter which appears exactly three times.
//    Multiplying these together produces a checksum of 4 * 3 = 12.

    /**
     * @dataProvider idDataProvider
     */
    public function testTwoOfAKind($data, $expected)
    {
        $result = Day2::nOfM(2, $data);

//        print_r($data);
//        print_r($result);

        $this->assertEquals($expected['2'], $result);
    }

    /**
     * @dataProvider idDataProvider
     */
    public function testThreeOfAKind($data, $expected)
    {
        $result = Day2::nOfM(3, $data);
        $this->assertEquals($expected['3'], $result);
    }

    /**
     * @dataProvider sumDataProvider
     */
    public function testChecksum($data, $expected)
    {
        $result = Day2::checkSum($data);
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider fabricDataProvider
     */
    public function testFindFabricId($data, $expected)
    {
        $result = Day2::fabricId($data);
        $this->assertEquals($expected, $result);
    }

    public function idDataProvider()
    {
        return [
            ['abcdef', ['2' => 0, '3' => 0]],
            ['bababc', ['2' => 1, '3' => 1]],
            ['abbcde', ['2' => 1, '3' => 0]],
            ['abcccd', ['2' => 0, '3' => 1]],
            ['aabcdd', ['2' => 1, '3' => 0]],
            ['abcdee', ['2' => 1, '3' => 0]],
            ['ababab', ['2' => 0, '3' => 1]],
        ];
    }

    public function sumDataProvider()
    {
        return [
            [['abcdef', 'bababc', 'abbcde', 'abcccd', 'aabcdd', 'abcdee', 'ababab'], 4 * 3],
        ];
    }

    public function fabricDataProvider()
    {
        return [
            [['abcde', 'fghij', 'klmno', 'pqrst', 'fguij', 'axcye', 'wvxyz'], 'fgij'],
        ];
    }
}
