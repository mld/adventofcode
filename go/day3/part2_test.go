package day3

import "testing"

func TestPartTwo(t *testing.T) {
  cases := []struct {
    in string
    out int
  }{
    {"^v", 3},
    {"^>v<", 3},
    {"^v^v^v^v^v",11},
  }

  for _, c := range cases {
    got := PartTwo(c.in)
    if got != c.out {
      t.Errorf("PartTwo(%q) == %n, want %n", c.in, got, c.out)
    }
  }
}
