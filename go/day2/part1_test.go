package day2

import "testing"

func TestPartOne(t *testing.T) {
  cases := []struct {
    in string
    out int
  }{
    {"2x3x4", 58},
    {"1x1x10", 43},
  }

  for _, c := range cases {
    got := PartOne(c.in)
    if got != c.out {
      t.Errorf("PartOne(%q) == %n, want %n", c.in, got, c.out)
    }
  }
}
