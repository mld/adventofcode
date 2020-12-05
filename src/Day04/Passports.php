<?php

namespace App\Day04;

class Passports
{
    /** @var array<mixed> */
    protected $list;

    /**
     * Passports constructor.
     * @param array<string> $rows
     */
    public function __construct(array $rows)
    {
        $this->parseInput($rows);
    }

    /**
     * @param array<string> $rows
     */
    public function parseInput(array $rows): void
    {
        $this->list = [];
        $raw = join("\n", $rows);
        $parts = explode("\n\n", $raw);
        printf("Found %d passports\n", count($parts));
        foreach ($parts as $row) {
            $inputs = preg_split('|\s|', $row);
            $passport = [];
            foreach ($inputs as $input) {
                $field = explode(':', $input);
                $passport[$field[0]] = $field[1];
            }
            $this->list[] = $passport;
        }
    }

    public static function isValid(array $passport, bool $simple = true): bool
    {
        $requiredFields = [
            'byr' => true,
            'iyr' => true,
            'eyr' => true,
            'hgt' => true,
            'hcl' => true,
            'ecl' => true,
            'pid' => true,
            'cid' => false,
        ];

        foreach ($requiredFields as $field => $required) {
            if ($required && (!isset($passport[$field]) || strlen($passport[$field]) == 0)) {
                return false;
            }
        }
        if ($simple) {
            return true;
        }

        $filters = [
            'byr' => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 1920, 'max_range' => 2002]],
            'iyr' => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 2010, 'max_range' => 2020]],
            'eyr' => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 2020, 'max_range' => 2030]],
            'hgt' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^[0-9]+(cm|in)$/']],
            'hcl' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '|^#[a-f0-9]{6}$|']],
            'ecl' => [
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => ['regexp' => '/^(amb|blu|brn|gry|grn|hzl|oth)$/']
            ],
            'pid' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '|^[0-9]{9}$|']],
        ];
        $filtered = filter_var_array($passport, $filters);

//        print_r(['passport' => $passport, 'filtered' => $filtered]);
        if (!isset($filtered['hgt'])) {
            return false;
        }

        if (strpos($filtered['hgt'], 'cm') !== false) {
            // if height is in cm, make sure it's between 150 and 193
            if (intval($filtered['hgt']) < 150 || intval($filtered['hgt']) > 193) {
                return false;
            }
        }

        if (strpos($filtered['hgt'], 'in') !== false) {
            // if height is in cm, make sure it's between 150 and 193
            if (intval($filtered['hgt']) < 59 || intval($filtered['hgt']) > 76) {
                return false;
            }
        }

        return self::isValid($filtered, true);
    }

    public function validPassports(bool $simple = true): int
    {
        $count = 0;
        foreach ($this->list as $passport) {
            if (self::isValid($passport, $simple)) {
                $count++;
            }
        }
        return $count;
    }
}
