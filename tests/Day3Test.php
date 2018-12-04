<?php
declare(strict_types=1);

namespace App\Tests;

use App\Day3;
use PHPUnit\Framework\TestCase;

final class Day3Test extends TestCase
{

// Each Elf has made a claim about which area of fabric would be ideal for Santa's suit. All claims have an ID and
// consist of a single rectangle with edges parallel to the edges of the fabric. Each claim's rectangle is defined as
// follows:
//
// The number of inches between the left edge of the fabric and the left edge of the rectangle.
// The number of inches between the top edge of the fabric and the top edge of the rectangle.
// The width of the rectangle in inches.
// The height of the rectangle in inches.
//
// A claim like #123 @ 3,2: 5x4 means that claim ID 123 specifies a rectangle 3 inches from the left edge, 2 inches from
// the top edge, 5 inches wide, and 4 inches tall. Visually, it claims the square inches of fabric represented by #
// (and ignores the square inches of fabric represented by .) in the diagram below:
//
//...........
//...........
//...#####...
//...#####...
//...#####...
//...#####...
//...........
//...........
//...........

    /**
     * @dataProvider fabricDataProvider
     */
    public function testParseCommands($data, $expected)
    {
        $result = Day3::parse($data);

        $this->assertEquals($expected['id'], $result['id']);
        $this->assertEquals($expected['left'], $result['left']);
        $this->assertEquals($expected['top'], $result['top']);
        $this->assertEquals($expected['wide'], $result['wide']);
        $this->assertEquals($expected['tall'], $result['tall']);
    }

    /**
     * @dataProvider fabricOverlapDataProvider
     */
    public function testFabricOverlap($data, $expected)
    {
        $overlap = Day3::calculateOverlap($data);
        $this->assertEquals($expected, $overlap);
    }

    /**
     * @dataProvider fabricNoOverlapDataProvider
     */
    public function testNoFabricOverlap($data, $expected)
    {
        $overlap = Day3::noOverlap($data);
        $this->assertEquals($expected, $overlap);
    }

    public function fabricDataProvider()
    {
        return [
            [
                '#1 @ 1,3: 4x4',
                ['id' => 1, 'left' => 1, 'top' => 3, 'wide' => 4, 'tall' => 4]
            ],
            [
                '#2 @ 3,1: 4x4',
                ['id' => 2, 'left' => 3, 'top' => 1, 'wide' => 4, 'tall' => 4]
            ],
            [
                '#3 @ 5,5: 2x2',
                ['id' => 3, 'left' => 5, 'top' => 5, 'wide' => 2, 'tall' => 2]
            ],
        ];
    }

    public function fabricOverlapDataProvider()
    {
        return [
            [
                [
                    '#1 @ 1,3: 4x4',
                    '#2 @ 3,1: 4x4',
                    '#3 @ 5,5: 2x2'
                ],
                4
            ]
        ];
    }

    public function fabricNoOverlapDataProvider()
    {
        return [
            [
                [
                    '#1 @ 1,3: 4x4',
                    '#2 @ 3,1: 4x4',
                    '#3 @ 5,5: 2x2'
                ],
                3
            ]
        ];
    }
}
