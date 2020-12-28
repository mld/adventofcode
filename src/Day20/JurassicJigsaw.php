<?php

namespace App\Day20;

class JurassicJigsaw
{
    /**
     * @var array<Tile>
     */
    protected array $tiles;

    /**
     * JurassicJigsaw constructor.
     * @param array<string> $input
     */
    public function __construct(array $input)
    {
        $rows = explode("\n\n", trim(join('', $input)));

        foreach ($rows as $row) {
            $tile = new Tile($row);
            $this->tiles[$tile->getId()] = $tile;
        }
    }

    public function part1(): int
    {
        $matchesPerTile = $this->findAdjacentTiles();
        $corners = [];
        foreach ($matchesPerTile as $tileK => $matchingTiles) {
            if (count($matchingTiles) === 2) {
                $corners[] = $this->tiles[$tileK]->getId();
            }
        }
        return $corners[0] * $corners[1] * $corners[2] * $corners[3];
    }

    public function part2(): int
    {
        $tilesPerEdge = sqrt(count($this->tiles));

        $matchesPerTile = $this->findAdjacentTiles();

        $corners = [];
        foreach ($matchesPerTile as $tileK => $matchingTiles) {
            if (count($matchingTiles) === 2) {
                $corners[$tileK] = $this->tiles[$tileK];
            }
        }

        // pick any corner, make it 0, 0
        $firstKey = array_key_first($corners);
        $grid = [
            0 => [
                0 => clone $corners[$firstKey],
            ],
        ];
        unset($this->tiles[$firstKey]);


        // find out which edges match
        $topMatches = $leftMatches = false;
        foreach ($matchesPerTile[$firstKey] as $tile) {
            $m = $grid[0][0]->findMatchingEdgesAndOrientation($tile);
            if ($m[0] === 't') {
                $topMatches = true;
            }
            if ($m[0] === 'l') {
                $leftMatches = true;
            }
        }


        // rotate so that they are r and b, use this as initial state
        if ($topMatches && $leftMatches) {
            $grid[0][0]->setOrientation(Tile::ORIENTATION_BOTTOM);
        } elseif ($leftMatches) {
            $grid[0][0]->setFlipped(true);
        } elseif ($topMatches) {
            $grid[0][0]->setOrientation(Tile::ORIENTATION_RIGHT);
        }

        // assemble the grid by matching the rows
        for ($y = 0; $y < $tilesPerEdge; $y++) {
            for ($x = 0; $x < $tilesPerEdge; $x++) {
                if ($x === 0 && $y === 0) {
                    continue;
                }

                if ($x === 0) {
                    $cur = $grid[$y - 1][$x];
                    foreach ($matchesPerTile[$cur->getId()] as $tile) {
                        if (!isset($this->tiles[$tile->getId()])) {
                            continue; // piece already taken rotating them would destroy the grid
                        }

                        $bottomEdge = $cur->getAppliedBottom();
                        foreach ([false, true] as $flip) {
                            $tile->setFlipped($flip);
                            foreach (Tile::ORIENTATIONS as $orientation) {
                                $tile->setOrientation($orientation);
                                if ($bottomEdge === $tile->getAppliedTop()) {
                                    $grid[$y][$x] = $tile;
                                    unset($this->tiles[$tile->getId()]);
                                    continue 4;
                                }
                            }
                        }
                    }
                }

                $cur = $grid[$y][$x - 1];
                foreach ($matchesPerTile[$cur->getId()] as $tile) {
                    if (!isset($this->tiles[$tile->getId()])) {
                        continue; // piece already taken rotating them would destroy the grid
                    }

                    $rightEdge = $cur->getAppliedRight();
                    foreach ([false, true] as $flip) {
                        $tile->setFlipped($flip);
                        foreach (Tile::ORIENTATIONS as $orientation) {
                            $tile->setOrientation($orientation);
                            if ($rightEdge === $tile->getAppliedLeft()) {
                                $grid[$y][$x] = clone $tile;
                                unset($this->tiles[$tile->getId()]);
                            }
                        }
                    }
                }
            }
        }

        // assemble image by returning the correct orientation and flip minus edges
        $bigGrid = [];
        foreach ($grid as $row) {
            $rawArrays = [];
            foreach ($row as $tile) {
                $rawArrays[] = $tile->getCroppedAndAlignedContent();
            }

            for ($i = 0, $iMax = count($rawArrays[0]); $i < $iMax; $i++) {
                $bigGrid[] = array_merge(...array_column($rawArrays, $i));
            }
        }

        // flip and rotate the image, and search for monsters until we find some
        foreach ([false, true] as $flip) {
            if ($flip) {
                $bigGrid = Grid::flipCols($bigGrid);
            }
            foreach ([0, 1, 2, 3] as $n) {
                $bigGrid = Grid::rotateLeft($bigGrid);
                $mapAsString = '';
                foreach ($bigGrid as $row) {
                    $mapAsString .= implode('', $row) . "\n";
                }
                $mapWithHighlights = $this->huntMonsters($mapAsString);
                if ($mapWithHighlights !== $mapAsString) {
                    break 2;
                }
            }
        }

//        printf("\n%s\n", $mapWithHighlights);
        $cnt = count_chars($mapWithHighlights, 1);
        if (!is_array($cnt)) {
            return 0;
        }
        return $cnt[ord('#')];
    }

    protected function huntMonsters(string $map): string
    {
        //             1111111111
        //   01234567890123456789
        // 0                   #
        // 1 #    ##    ##    ###
        // 2  #  #  #  #  #  #

//        $monster = [
//            0 => [18],
//            1 => [0, 5, 6, 11, 12, 17, 18, 19],
//            2 => [1, 4, 7, 10, 13, 16]
//        ];

        $mapHeight = $mapWidth = strpos($map, "\n");
        for ($x = 0; $x < $mapWidth - 20; $x++) {
            for ($y = 0; $y < $mapHeight - 3; $y++) {
                $relativeMonsterPosition = [
                    0 => ($mapWidth + 1) * ($y + 0) + $x,
                    1 => ($mapWidth + 1) * ($y + 1) + $x,
                    2 => ($mapWidth + 1) * ($y + 2) + $x,
                ];
                if (
                    $map[$relativeMonsterPosition[0] + 18] == '#' &&
                    $map[$relativeMonsterPosition[1] + 0] == '#' &&
                    $map[$relativeMonsterPosition[1] + 5] == '#' &&
                    $map[$relativeMonsterPosition[1] + 6] == '#' &&
                    $map[$relativeMonsterPosition[1] + 11] == '#' &&
                    $map[$relativeMonsterPosition[1] + 12] == '#' &&
                    $map[$relativeMonsterPosition[1] + 17] == '#' &&
                    $map[$relativeMonsterPosition[1] + 18] == '#' &&
                    $map[$relativeMonsterPosition[1] + 19] == '#' &&
                    $map[$relativeMonsterPosition[2] + 1] == '#' &&
                    $map[$relativeMonsterPosition[2] + 4] == '#' &&
                    $map[$relativeMonsterPosition[2] + 7] == '#' &&
                    $map[$relativeMonsterPosition[2] + 10] == '#' &&
                    $map[$relativeMonsterPosition[2] + 13] == '#' &&
                    $map[$relativeMonsterPosition[2] + 16] == '#'
                ) {
                    $map[$relativeMonsterPosition[0] + 18] = 'O';
                    $map[$relativeMonsterPosition[1] + 0] = 'O';
                    $map[$relativeMonsterPosition[1] + 5] = 'O';
                    $map[$relativeMonsterPosition[1] + 6] = 'O';
                    $map[$relativeMonsterPosition[1] + 11] = 'O';
                    $map[$relativeMonsterPosition[1] + 12] = 'O';
                    $map[$relativeMonsterPosition[1] + 17] = 'O';
                    $map[$relativeMonsterPosition[1] + 18] = 'O';
                    $map[$relativeMonsterPosition[1] + 19] = 'O';
                    $map[$relativeMonsterPosition[2] + 1] = 'O';
                    $map[$relativeMonsterPosition[2] + 4] = 'O';
                    $map[$relativeMonsterPosition[2] + 7] = 'O';
                    $map[$relativeMonsterPosition[2] + 10] = 'O';
                    $map[$relativeMonsterPosition[2] + 13] = 'O';
                    $map[$relativeMonsterPosition[2] + 16] = 'O';
                }
            }
        }
        return $map;
    }

    /**
     * @return array<array<int,Tile>>
     */
    protected function findAdjacentTiles(): array
    {
        $matchesPerTile = [];
        // we deliberately check a -> b and then b -> a again, as we might need that for lookup later.
        foreach ($this->tiles as $k1 => $tile1) {
            $matchesPerTile[$k1] = [];
            foreach ($this->tiles as $k2 => $tile2) {
                if ($k1 === $k2) {
                    continue;
                }
                if ($tile1->hasMatchingEdge($tile2)) {
                    $matchesPerTile[$k1][] = $tile2;
                }
            }
        }
        return $matchesPerTile;
    }
}
