<?php

declare(strict_types=1);

namespace App\Tests\Day02;

use App\Day02;

class PasswordPolicyTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider passwordPolicyDataProvider
     * @param array<string> $rows
     * @param bool $mode
     * @param int $expected
     * @covers       \App\Day02\PasswordPolicy
     */
    public function testTobogganTrajectory(array $rows, bool $mode, int $expected)
    {
        $module = new Day02\PasswordPolicy($rows, $mode);

        $this->assertEquals($expected, $module->validPasswordCount());
    }

    public function passwordPolicyDataProvider()
    {
        $policies = [
            '1-3 a: abcde',
            '1-3 b: cdefg',
            '2-9 c: ccccccccc',
        ];
        return [
            [$policies, true, 1],
            [$policies, false, 2],
        ];
    }
}
