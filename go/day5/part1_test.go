package day5

import "testing"

func TestPartOne(t *testing.T) {
  cases := []struct {
    in string
    out bool
  }{
    {"ugknbfddgicrmopn", true},
    {"aaa", true},
    {"jchzalrnumimnmhp", false},
    {"haegwjzuvuyypxyu", false},
    {"dvszwmarrgswjxmb", false},
  }

  for _, c := range cases {
    got := PartOne(c.in)
    if got != c.out {
      t.Errorf("PartOne(%q) == %q, want %q", c.in, got, c.out)
    }
  }
}
