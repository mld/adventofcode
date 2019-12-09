<?php
declare(strict_types=1);

namespace App\Tests\Day08;

use App\Day08\SpaceImageFormat;
use PHPUnit\Framework\TestCase;

final class SpaceImageFormatTest extends TestCase
{

    /**
     * @dataProvider stateMachineV2DataProvider
     * @param $input
     * @param $expected int
     */
    public function testSpaceImageFormatErrorCode($input, $expected)
    {
        $sif = new SpaceImageFormat($input[0], $input[1], $input[2]);
        $this->assertEquals($expected, $sif->errorCorrectionCode());
    }

    public function stateMachineV2DataProvider()
    {
        return [
            '123456789012' => [["123456789012", 2, 3], 1],
        ];
    }

}
