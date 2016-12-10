package day2

import (
  "strings"
  "strconv"
)

func PartTwo(input string) int {
  present := 0
  bow := 0

  s := strings.SplitN(input,"x",4)
  l, _ := strconv.Atoi(s[0])
  w, _ := strconv.Atoi(s[1])
  h, _ := strconv.Atoi(s[2])

  bow += w*h*l

  side := l
  if w > side {
    side = w
  }
  if h > side {
    side = h
  }

  present = 2*l+2*h+2*w-2*side
    
  return present+bow
}
