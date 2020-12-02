<?php
declare(strict_types=1);


namespace App\Tests\Day01;

use App\Day01;


class ExpenseReportTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider expenseTwoDataProvider
     * @param array<int> $rows
     * @param int $expected
     * @covers \App\Day01\ExpenseReport
     */
    public function testExpenseTwo(array $rows, int $expected)
    {
        $module = new Day01\ExpenseReport($rows);

        $this->assertEquals($expected, array_product( $module->getRowsBySum()));
    }

    /**
     * @dataProvider expenseThreeDataProvider
     * @param array<int> $rows
     * @param int $expected
     * @covers \App\Day01\ExpenseReport
     */
    public function testExpenseThree(array $rows, int $expected)
    {
        $module = new Day01\ExpenseReport($rows);
        $this->assertEquals($expected, array_product($module->getThreeRowsBySum()));
    }

    public function expenseTwoDataProvider()
    {
        return [
            [[1721, 979, 366, 299, 675, 1456], 514579]
        ];
    }

    public function expenseThreeDataProvider()
    {
        return [
            [[1721, 979, 366, 299, 675, 1456], 241861950]
        ];
    }
}