package day3

import "testing"

func TestPartOne(t *testing.T) {
  cases := []struct {
    in string
    out int
  }{
    {">", 2},
    {"^>v<", 4},
    {"^v^v^v^v^v",2},
  }

  for _, c := range cases {
    got := PartOne(c.in)
    if got != c.out {
      t.Errorf("PartOne(%q) == %n, want %n", c.in, got, c.out)
    }
  }
}
