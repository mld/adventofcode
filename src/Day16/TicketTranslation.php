<?php

namespace App\Day16;

class TicketTranslation
{
    /**
     * @var array<mixed>
     */
    protected array $rules;
    /**
     * @var array<int>
     */
    protected array $yourTicket;
    /**
     * @var array<mixed>
     */
    protected array $nearbyTickets;

    /**
     * RainRisk constructor.
     * @param array<string> $rows
     */
    public function __construct(array $rows)
    {
        $this->parse($rows);
    }

    /**
     * @param array<string> $input
     */
    public function parse(array $input): void
    {
        // first the rules
        $section = 0;
        foreach ($input as $line) {
            $line = trim($line);
            if (strlen($line) == 0) {
                $section++;
                continue;
            }

            if ($section == 0) {
                list($label, $rule) = explode(':', $line);

                $ruleParts = preg_split('/ |-/', trim($rule));
                if (!is_array($ruleParts)) {
                    continue;
                }
//                printf("%s rule parts: %s\n", $label, join(', ', $ruleParts));
                $this->rules[$label] = [
                    'lower' => [(int)$ruleParts[0], (int)$ruleParts[1]],
                    'higher' => [(int)$ruleParts[3], (int)$ruleParts[4]],
                ];
            } else {
                if (strpos($line, 'your') === 0) {
                    // ignore header
                    continue;
                }
                if (strpos($line, 'nearby') === 0) {
                    // ignore header
                    continue;
                }
                $numbers = array_map('intval', explode(',', $line));
                if ($section == 1) {
                    $this->yourTicket = $numbers;
                } else {
                    $this->nearbyTickets[] = $numbers;
                }
            }
        }
    }

    /**
     * @param int $number
     * @return array<string>
     */
    public function getPossibleFields(int $number): array
    {
        $fields = [];
        foreach ($this->rules as $id => $rule) {
            if ($number >= $rule['lower'][0] && $number <= $rule['lower'][1]) {
                $fields[] = $id;
                continue;
            }
            if ($number >= $rule['higher'][0] && $number <= $rule['higher'][1]) {
                $fields[] = $id;
                continue;
            }
        }

        return $fields;
    }

    public function part1(): int
    {
        $invalidFields = [];
        foreach ($this->nearbyTickets as $ticketId => $ticket) {
            foreach ($ticket as $number) {
                $fields = $this->getPossibleFields($number);
                if (count($fields) == 0) {
                    $invalidFields[] = (int)$number;
                }
            }
        }

        return intval(array_sum($invalidFields));
    }

    public function part2(): int
    {
        $this->printRules();

        $possibleFieldPositions = [];
        $tickets = 0;
        foreach ($this->nearbyTickets as $ticketId => $ticket) {
            foreach ($ticket as $field => $number) {
                $fields = $this->getPossibleFields($number);
                if (count($fields) == 0) {
                    continue;
                }
                $possibleFieldPositions[$field][$ticketId] = $fields;
                $tickets++;
            }
        }

        $fieldPosition = [];
        do {
            foreach (array_keys($possibleFieldPositions) as $position) {
                if (isset($fieldPosition[$position])) {
                    continue;
                }
                $field = array_intersect(...$possibleFieldPositions[$position]);
                $field = array_diff($field, $fieldPosition);
                if (count($field) == 1) {
                    $fieldPosition[$position] = reset($field);
                    printf("Field % 2d determined: %s\n", $position, join(',', $field));
                }
            }
        } while (count($fieldPosition) != count($this->rules));

        ksort($fieldPosition);
        $product = 1;
        foreach ($fieldPosition as $pos => $label) {
            if ($label !== false && stripos($label, 'departure') === 0) {
                $product *= $this->yourTicket[$pos];
            }
        }
        return $product;
    }

    public function printRules(): void
    {
        printf("=== Rules === \n");
        foreach ($this->rules as $id => $rule) {
            printf(
                "%s: %d-%d or %d-%d\n",
                $id,
                $rule['lower'][0],
                $rule['lower'][1],
                $rule['higher'][0],
                $rule['higher'][1]
            );
        }
        printf("\n");
    }
}
