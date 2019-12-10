<?php
declare(strict_types=1);

namespace App\Tests\Day10;

use PHPUnit\Framework\TestCase;

final class AsteroidMapTest extends TestCase
{
    /**
     * @dataProvider AsteroidMapDataProvider
     * @param $input
     * @param $expected int
     */
    public function testAsteroidMapLOS($input, $expected)
    {
        $map = new \App\Day10\AsteroidMap($input, false);
        $result = $map->findBestAsteroid();
        $this->assertEquals($expected[0], $result[0]->toArray());
        $this->assertEquals($expected[1], $result[1]);
    }


    public function AsteroidMapDataProvider()
    {
        return [
            'test1' => [file_get_contents('tests/Day10/test1.txt'), [[3, 4], 8]],
            'test2' => [file_get_contents('tests/Day10/test2.txt'), [[5, 8], 33]],
            'test3' => [file_get_contents('tests/Day10/test3.txt'), [[1, 2], 35]],
            'test4' => [file_get_contents('tests/Day10/test4.txt'), [[6, 3], 41]],
            'test5' => [file_get_contents('tests/Day10/test5.txt'), [[11, 13], 210]],
        ];
    }

    /**
     * @dataProvider AsteroidDestructionSequenceDataProvider
     * @param $input
     * @param $expected int
     */
    public function testAsteroidDestruction($input, $expected)
    {
        $map = new \App\Day10\AsteroidMap($input[0], false);
        $result = $map->vaporizer($input[1]);
        $this->assertEquals($expected, $result[0]->toArray());
    }

    public function AsteroidDestructionSequenceDataProvider()
    {
        $input = file_get_contents('tests/Day10/test5.txt');
        return [
            'test5-boom-1  ' => [[$input, 1], [11, 12]],
            'test5-boom-2  ' => [[$input, 2], [12, 1]],
            'test5-boom-3  ' => [[$input, 3], [12, 2]],
            'test5-boom-10 ' => [[$input, 10], [12, 8]],
            'test5-boom-20 ' => [[$input, 20], [16, 0]],
            'test5-boom-50 ' => [[$input, 50], [16, 9]],
            'test5-boom-100' => [[$input, 100], [10, 16]],
            'test5-boom-199' => [[$input, 199], [9, 6]],
            'test5-boom-200' => [[$input, 200], [8, 2]],
            'test5-boom-201' => [[$input, 201], [10, 9]],
        ];
    }

}
