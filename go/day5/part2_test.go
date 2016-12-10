package day5

import "testing"

func TestPartTwo(t *testing.T) {
  cases := []struct {
    in string
    out bool
  }{
    {"qjhvhtzxzqqjkmpb", true},
    {"xxyxx", true},
    {"uurcxstgmygtbstg", false},
    {"ieodomkazucvgmuy", false},
  }

  for _, c := range cases {
    got := PartTwo(c.in)
    if got != c.out {
      t.Errorf("PartTwo(%q) == %q, want %q", c.in, got, c.out)
    }
  }
}
