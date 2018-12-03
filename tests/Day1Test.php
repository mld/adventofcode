<?php
declare(strict_types=1);

namespace App\Tests;

use App\Day1;
use PHPUnit\Framework\TestCase;

final class Day1Test extends TestCase
{

    /**
     * @dataProvider frequencyDataProvider
     */
    public function testFrequency($a, $b, $c, $expected)
    {
        $sum = \App\Day1::getFrequency([$a, $b, $c]);
        $this->assertEquals($expected, $sum);
    }

    public function frequencyDataProvider()
    {
        return [
            '+1+1+1' => ['+1', '+1', '+1', 3],
            '+1+1-2' => ['+1', '+1', '-2', 0],
            '-1-2-3' => ['-1', '-2', '-3', -6],
        ];
    }


    /**
     * @dataProvider duplicateDataProvider
     */
    public function testDuplicate($data, $expected)
    {
        $result = Day1::getDuplicate($data);
        $this->assertEquals($expected, $result);
    }

    public function duplicateDataProvider()
    {
        $this->assertEmpty('', '');

        return [

            '+1, -2, +3, +1' => [['+1', '-2', '+3', '+1'], 2],
            '+1, -1' => [['+1', '-1'], 0],
            '+3, +3, +4, -2, -4' => [['+3', '+3', '+4', '-2', '-4'], 10],
            '-6, +3, +8, +5, -6' => [['-6', '+3', '+8', '+5', '-6'], 5],
            '+7, +7, -2, -7, -4' => [['+7', '+7', '-2', '-7', '-4'], 14],
        ];
    }

}
