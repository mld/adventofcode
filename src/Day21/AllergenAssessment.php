<?php

namespace App\Day21;

class AllergenAssessment
{
    /**
     * @var array<mixed>
     */
    protected array $products;

    /**
     * AllergenAssessment constructor.
     * @param array<string> $input
     */
    public function __construct(array $input)
    {
        foreach ($input as $row) {
            $row = trim($row);
            if (strlen($row) == 0) {
                continue;
            }

            [$ingredients, $allergens] = explode(' (contains ', $row);
            $this->products[] = [
                'ingredients' => explode(' ', $ingredients),
                'allergens' => explode(', ', substr($allergens, 0, -1))
            ];
        }
    }

    public function part1(): int
    {
        $ingredientCount = array_count_values(array_merge(...array_column($this->products, 'ingredients')));
        $knownAllergens = $this->getKnownAllergens();

        // take the count of all ingredients, then remove the allergens from the list
        $safeIngredientCount = $ingredientCount;
        foreach ($knownAllergens as $allergen => $ingredient) {
            unset($safeIngredientCount[$ingredient]);
        }

        return (int)array_sum($safeIngredientCount);
    }

    public function part2(): string
    {
        $knownAllergens = $this->getKnownAllergens();
        ksort($knownAllergens);
        return join(",", $knownAllergens);
    }

    /**
     * @return array<int|string,string>
     */
    public function getKnownAllergens(): array
    {
        // first, find the number of possible ingredients per allergen
        $allergenCandidates = [];
        foreach ($this->products as $product) {
            foreach ($product['allergens'] as $allergen) {
                if (!isset($allergenCandidates[$allergen])) {
                    $allergenCandidates[$allergen] = $product['ingredients'];
                } else {
                    $allergenCandidates[$allergen] = array_intersect(
                        $allergenCandidates[$allergen],
                        $product['ingredients']
                    );
                }
            }
        }

        // then we work our way through the list, eliminating all allergens with only one possible ingredient at a time
        $knownAllergens = [];
        while (count($knownAllergens) !== count($allergenCandidates)) {
            foreach ($allergenCandidates as $allergen => $ingredient) {
                if (isset($knownAllergens[$allergen])) {
                    continue;
                }
                $left = array_diff($ingredient, $knownAllergens);
                if (count($left) === 1) {
                    $knownAllergens[$allergen] = array_pop($left);
                }
            }
        }

        return $knownAllergens;
    }
}
