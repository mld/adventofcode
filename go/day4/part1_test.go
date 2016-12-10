package day4

import "testing"

func TestPartOne(t *testing.T) {
  cases := []struct {
    in string
    out int
  }{
    {"abcdef", 609043},
    {"pqrstuv", 1048970},
  }

  for _, c := range cases {
    got := PartOne(c.in)
    if got != c.out {
      t.Errorf("PartOne(%q) == %n, want %n", c.in, got, c.out)
    }
  }
}
