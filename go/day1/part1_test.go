package day1

import "testing"

func TestPartOne(t *testing.T) {
  cases := []struct {
    in []byte
    out int
  }{
    {[]byte("(())"), 0},
    {[]byte("()()"), 0},
    {[]byte("((("), 3},
    {[]byte("(()(()("), 3},
    {[]byte("))((((("), 3},  
    {[]byte("())"), -1},  
    {[]byte("))("), -1},  
    {[]byte(")))"), -3},  
    {[]byte(")())())"), -3},  
  }

  for _, c := range cases {
    got := PartOne(c.in)
    if got != c.out {
      t.Errorf("PartOne(%q) == %n, want %n", c.in, got, c.out)
    }
  }
}
