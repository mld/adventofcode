<?php


namespace App\Day06;


class UniversalOrbitMap
{
    protected $map;
    protected $debug;

    public function __construct($mapData = [], $debug = false)
    {
        $this->debug = $debug;
        $this->parseMapData($mapData);
    }

    protected function parseMapData($mapData = [])
    {
        foreach ($mapData as $item) {
            if (strpos($item, ')') !== false) {
                list($a, $b) = explode(')', trim($item));
                $this->map[$a][] = $b;
                if (!isset($this->map[$b])) {
                    $this->map[$b] = [];
                }
            }
        }

//        print_r($this->map);
    }

//    public function stepsToCOM($findObject)
//    {
//        if (in_array($findObject, $this->map['COM'])) {
//            return 1;
//        }
//
//        foreach ($this->map as $k => $i) {
//            if (in_array($findObject, $i)) {
//                return 1 + $this->stepsToCOM($k);
//            }
//        }
//    }

    public function stepsBetween($source, $destination = 'COM')
    {
        if ($source == $destination) {
            return 0;
        } elseif (in_array($source, $this->map[$destination])) {
            return 1;
        }

        foreach ($this->map as $k => $i) {
            if (in_array($source, $i)) {
                return 1 + $this->stepsBetween($k, $destination);
            }
        }

        return false;
    }

    public function orbitCounter()
    {
        $output = 0;

        foreach (array_keys($this->map) as $object) {
//            $sum = $this->stepsToCOM($object);
            $sum = $this->stepsBetween($object, 'COM');
//            printf("Fetching orbiter count for %s: %d\n", $object, $sum);
            $output += $sum;
        }
        return $output;
    }

    public function shortestPath($pointA = 'SAN', $pointB = 'YOU')
    {
//        printf("\nMap:\n%s\n", print_r($this->map, true));
        $start = $this->parentOf($pointA);
        $destination = $this->parentOf($pointB);

        // Find first parent of $start that has a direct line down to $destination
//        $child = false;
        $distanceToCommonParent = 0;
        $root = $start;
        $child = false;
        $ignored = [];
        do {
//            printf("S: Shortest Path from %s to %s: %s(%d)\n", $root, $destination, var_export($child, true),
//                $distanceToCommonParent);

            $child = $this->findChild($root, $destination);

            if ($child === false) {
                $ignored[] = $root;
                $distanceToCommonParent++;
                $root = $this->parentOf($root);
            }

//            printf("E: Shortest Path from %s to %s: %s(%d)\n", $root, $destination, var_export($child, true),
//                $distanceToCommonParent);
        } while ($child === false);

//        return $this->stepsBetween($start, $child) + $this->stepsBetween($destination, 'YOU');
        return $child + $distanceToCommonParent;
    }

    /**
     * @param $point
     * @return mixed
     */
    protected function parentOf($point)
    {
        foreach (array_keys($this->map) as $object) {
            if (in_array($point, $this->map[$object])) {
//                printf("%s is parent of %s\n", $object, $point);
                return $object;
            }
        }
        return false;
    }

    /**
     * @param $point
     * @return mixed
     */
    protected function findChild($root, $point, $depth = 1, $ignored = [])
    {
//        printf("Findchild with root %s, searching for %s, depth %d\n", $root, $point, $depth);
        foreach ($this->map[$root] as $object) {
            if(in_array($object,$ignored)) {
//                printf("%s/%s ignored\n", $root, $object);
                continue;
            }

//            printf("See if %s/%s has child %s\n", $root, $object, $point);
            if (in_array($point, $this->map[$object])) {
//                printf("%s is direct child of %s/%s: %s\n", $point,$root, $object, $depth + 1);
                return $depth + 1;
            }
            $found = $this->findChild($object, $point, $depth + 1, $ignored);
            if ($found !== false) {
//                printf("See if %s/%s has child %s : %s\n", $root, $object, $point, $found);

                return $found;
            }
        }
//        printf("See if %s has child %s : %s\n", $root, $point, var_export(false, true));
        return false;
    }


}