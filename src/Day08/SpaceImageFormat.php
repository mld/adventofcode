<?php


namespace App\Day08;


class SpaceImageFormat
{
    protected $raw;
    protected $height;
    protected $width;
    protected $debug;
    protected $layers;

    public function __construct($raw, $height, $width, $debug = false)
    {
        $this->raw = $raw;
        $this->height = $height;
        $this->width = $width;
        $this->debug = $debug;
        $this->layers = [];
        $this->parseRaw();
    }

    public function parseRaw()
    {
        $rows = str_split(trim($this->raw), $this->width);
        $layer = 0;
        $rowCount = 0;
        foreach ($rows as $k => $row) {
            if ($rowCount == $this->height) {
                $layer++;
                $rowCount = 0;
            }
            $this->layers[$layer][] = str_split(trim($row));
            $rowCount++;
        }
    }

    public function printImageLayers()
    {
        foreach ($this->layers as $layerId => $layer) {
            printf("Layer %2d:\n", $layerId);
            foreach ($layer as $row) {
                printf("          %s\n", join('', $row));
            }
            if (count($this->layers) > $layerId) {
                printf("\n");
            }
        }
    }

    public function printImage()
    {
        $final = [];
        foreach ($this->layers as $layerId => $layer) {
            foreach ($layer as $rowId => $row) {
                foreach ($row as $colId => $col) {
                    if (!isset($final[$rowId][$colId])) {
                        $final[$rowId][$colId] = $col;
                        continue;
                    }
                    switch ($final[$rowId][$colId]) {
                        case 0:
                        case 1:
                            // ignore
                            break;
                        case 2:
                            $final[$rowId][$colId] = $col;
                    }
                }
            }
        }

        foreach ([0, 1, 2] as $color) {
            printf("Color: %s\n",$color);
            foreach ($final as $row) {
                printf("          ");
                foreach ($row as $col) {
                    if ($col == $color) {
                        printf("%c",35);
                    } else {
                        printf(" ");

                    }
                }
                printf("\n");
            }
            printf("\n");
        }
        printf("\n");
    }

    public function errorCorrectionCode()
    {
        $stats = [];

        foreach ($this->layers as $layerId => $layer) {
            $stats[$layerId] = [0 => 0, 1 => 0, 2 => 0];
            foreach ($layer as $row) {
                $rowStats = array_count_values($row);
                $stats[$layerId][0] += $rowStats['0'];
                $stats[$layerId][1] += $rowStats['1'];
                $stats[$layerId][2] += $rowStats['2'];
            }
        }

        if (count($stats) == 0) {
            return false;
        }

        uasort($stats, function ($a, $b) {
            if ($a[0] == $b[0]) {
                return 0;
            }
            return ($a[0] < $b[0] ? -1 : 1);
        });

        $layer = reset($stats);
//        print_r($layer);
        return ($layer[1] * $layer[2]);
    }
}