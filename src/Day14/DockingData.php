<?php

namespace App\Day14;

class DockingData
{
    /** @var array<int> */
    protected array $storePart1;

    /** @var array<int> */
    protected array $storePart2;

    /**
     * RainRisk constructor.
     * @param array<string> $rows
     */
    public function __construct(array $rows)
    {
        $this->parsePart1($rows);
        $this->parsePart2($rows);
    }

    /**
     * @param array<mixed> $rows
     */
    public function parsePart1(array $rows): void
    {
        $this->storePart1 = [];
        $mask = [0 => 0, 1 => 0];
        foreach ($rows as $row) {
            $row = trim($row);
            if (strlen($row) < 1) {
                continue;
            }
            if (strpos($row, 'mask = ') === 0) {
                // we've got a bitmask
//                printf("Bitmask:   %s\n", substr($row, 7));
                $mask = [0 => 0, 1 => 0];
                foreach (str_split(substr($row, 7)) as $bit => $val) {
                    if ($val == 'X') {
                        continue;
                    }
                    $mask[(int)$val] += pow(2, 35 - $bit);
//                    printf(" > % 2d:%s => %036b\n", $bit, $val, $mask[(int)$val]);
                }
//                printf("*      %s\n     1 %036b\n     0 %036b\n", substr($row, 7), $mask[1], $mask[0]);
            } elseif (strpos($row, 'mem') === 0) {
                sscanf($row, 'mem[%d] = %d', $box, $val);
                $this->storePart1[$box] = ($val | $mask[1]) & ~$mask[0];
//                printf("+% 5d %036b (%d)\n", $box, $this->store[$box], $val);
            } else {
                printf("!! %s\n", $row);
            }
        }
    }

    /**
     * @param array<mixed> $rows
     */
    public function parsePart2(array $rows): void
    {
        $this->storePart2 = [];
        $mask = [0 => 0, 1 => 0, 'X' => []];

        foreach ($rows as $row) {
            $row = trim($row);
            if (strlen($row) < 1) {
                continue;
            }
            if (strpos($row, 'mask = ') === 0) {
                // we've got a bitmask
//                printf("Bitmask:   %s\n", substr($row, 7));
                $mask = [0 => 0, 1 => 0, 'X' => []];
                foreach (str_split(substr($row, 7)) as $bit => $val) {
                    if ($val == 'X') {
                        $mask['X'][] = 35 - $bit;
                    } else {
                        $mask[(int)$val] += pow(2, 35 - $bit);
//                    printf(" > % 2d:%s => %036b\n", $bit, $val, $mask[(int)$val]);
                    }
                }
//                printf("*      %s\n     1 %036b\n     0 %036b\n       %s\n",
//                    substr($row, 7),
//                    $mask[1],
//                    $mask[0],
//                    join(',', $mask['X'])
//                );
            } elseif (strpos($row, 'mem') === 0) {
                sscanf($row, 'mem[%d] = %d', $box, $val);
                if (count($mask['X']) == 0) {
                    $boxes[] = ($box | $mask[1]);
                } else {
                    $boxes = $this->flipBits(($box | $mask[1]), $mask['X']);
                }
                foreach ($boxes as $item) {
                    $this->storePart2[$item] = $val;
                }
                //                $this->storePart2[$box] = ($val | $mask[1]) & ~$mask[0];
//                printf("+% 5d %036b (%d)\n", $box, $this->store[$box], $val);
            } else {
                printf("!! %s\n", $row);
            }
        }
    }

    public function part1(): int
    {
        return (int)array_sum($this->storePart1);
    }

    public function part2(): int
    {
//        printf("\n=== Part 2 ===\n");
//        sort($this->storePart2);
//        foreach ($this->storePart2 as $address => $value) {
//            printf("%036b =  %d\n", $address, $value);
//        }
        return (int)array_sum($this->storePart2);
    }

    /**
     * @param int $address
     * @param array<int> $bits
     * @param int $depth
     * @return array<int>
     */
    protected function flipBits(int $address, array $bits, $depth = 0): array
    {
//        $pre = str_repeat(' ', $depth * 2);
        $bit = array_shift($bits);
        if ($bit === null) {
            return [];
        }

//        $this->storePart1[$box] = ($val | $mask[1]) & ~$mask[0];

        $mask = pow(2, $bit);
        $address0 = $address | $mask;
        $address1 = $address & ~$mask;
        $return = [$address0, $address1];
//        printf("%d with bit %d flipped becomes %s\n",)


//        printf("%s - %036b : %d (%s)\n", $pre, $address, $bit, join(',', $bits));
//        printf("%s > %036b\n", $pre, $address0);
//        printf("%s > %036b\n", $pre, $address1);

        $return = array_merge(
            [$address0, $address1],
            $this->flipBits($address0, $bits, $depth + 1),
            $this->flipBits($address1, $bits, $depth + 1)
        );
        return array_unique($return);
    }
}
