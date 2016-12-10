package day2

import "testing"

func TestPartTwo(t *testing.T) {
  cases := []struct {
    in string
    out int
  }{
    {"2x3x4", 34},
    {"1x1x10", 14},
  }

  for _, c := range cases {
    got := PartTwo(c.in)
    if got != c.out {
      t.Errorf("PartTwo(%q) == %n, want %n", c.in, got, c.out)
    }
  }
}
