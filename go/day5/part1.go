package day5

import (
  "strings"
)

// A nice string is one with all of the following properties:
// - It contains at least three vowels (aeiou only), like aei, xazegov, or aeiouaeiouaeiou.
// - It contains at least one letter that appears twice in a row, like xx, abcdde (dd), or aabbccdd (aa, bb, cc, or dd).
// - It does not contain the strings ab, cd, pq, or xy, even if they are part of one of the other requirements.


func PartOne(input string) bool {
  vowels := 0
  doubles := 0
  bad := 0
  last := ""

  line := strings.Split(input, "")
  for _, char := range line {
    switch(char) {
    case "a", "e", "i", "o", "u":
      vowels++
    }

    if char == last {
      doubles++
    }

    if last == "a" && char == "b" {
      bad++
    } else if last == "c" && char == "d" {
      bad++
    } else if last == "p" && char == "q" {
      bad++
    } else if last == "x" && char == "y" {
      bad++
    }

    last = char
  }

  if vowels >= 3 && doubles >= 1 && bad == 0 {
    return true
  }

  return false
}
