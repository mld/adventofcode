package day2

import (
  "strings"
  "strconv"
)

func PartOne(input string) int {
  paper := 0

  s := strings.SplitN(input,"x",4)
  l, _ := strconv.Atoi(s[0])
  w, _ := strconv.Atoi(s[1])
  h, _ := strconv.Atoi(s[2])

  paper += 2*l*w + 2*w*h + 2*h*l

  side := l * w
  if w * h < side {
    side = w * h
  }
  if h * l < side {
    side = h * l
  }

  paper += side
    
  return paper
}
