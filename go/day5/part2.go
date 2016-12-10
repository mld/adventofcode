package day5

import (
  "strings"
)

// Now, a nice string is one with all of the following properties:
// - It contains a pair of any two letters that appears at least twice in the string without overlapping,
//   like xyxy (xy) or aabcdefgaa (aa), but not like aaa (aa, but it overlaps).
// - It contains at least one letter which repeats with exactly one letter between them, like xyx,
//   abcdefeghi (efe), or even aaa.


func PartTwo(input string) bool {
  doubles := 0
  repeated := 0
  lasttwo := ""
  lastone := ""

  line := strings.Split(input, "")
  for _, c := range line {

    if c == lasttwo {
      repeated++
    }

    if lastone != "" && strings.Count(input,string(lastone+c)) > 1 {
      doubles++
    }

    lasttwo = lastone
    lastone = c
  }

  if repeated >= 1 && doubles >= 1 {
    return true
  }
  return false
}
