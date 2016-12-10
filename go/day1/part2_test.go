package day1

import "testing"

func TestPartTwo(t *testing.T) {
  cases := []struct {
    in []byte
    out int
  }{
    {[]byte(")"), 1},
    {[]byte("()())"), 5},
  }

  for _, c := range cases {
    got := PartTwo(c.in)
    if got != c.out {
      t.Errorf("PartTwo(%q) == %n, want %n", c.in, got, c.out)
    }
  }
}
