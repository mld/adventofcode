<?php
declare(strict_types=1);

namespace App\Tests\Day04;

use App\Day04\Passwords;
use PHPUnit\Framework\TestCase;

final class PasswordsTest extends TestCase
{

    /**
     * @dataProvider passwordDataProvider
     * @param $password
     * @param $expected int
     */
    public function testDoubleAscending($password, $expected)
    {
        $pw = new Passwords();

        $this->assertEquals($expected, $pw::DoubleAscendingCheck($password));
    }

    public function passwordDataProvider()
    {
        return [
            '111111' => [
                '111111',
                true
            ],
            '223450' => [
                '223450',
                false
            ],
            '123789' => [
                '123789',
                false
            ],
        ];
    }

    /**
     * @dataProvider password2DataProvider
     * @param $password
     * @param $expected int
     */
    public function testDoubleNoTripleAscending($password, $expected)
    {
        $pw = new Passwords();

        $this->assertEquals($expected, $pw::DoubleAscendingNoTripleCheck($password));
    }

    public function password2DataProvider()
    {
        return [
            '112233' => [
                '112233',
                true
            ],
            '123444' => [
                '123444',
                false
            ],
            '111122' => [
                '111122',
                true
            ],
        ];
    }
}
