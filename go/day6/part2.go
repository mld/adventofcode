package day6

import (
  "fmt"
)

func PartTwo(input []string) int {
  var lights [1000][1000]int

  for _, line := range input {
    var x0, y0, x1, y1, n int

    n, _ = fmt.Sscanf(line, "turn on %d,%d through %d,%d\n", &x0, &y0, &x1, &y1)
    if n == 4 {
      for y := y0; y <= y1; y++ {
        for x := x0; x <= x1; x++ {
          lights[y][x]++
        }
      }
    }
    n, _ = fmt.Sscanf(line, "turn off %d,%d through %d,%d\n", &x0, &y0, &x1, &y1)
    if  n == 4 {
      for y := y0; y <= y1; y++ {
        for x := x0; x <= x1; x++ {
          if lights[y][x] > 0 {
            lights[y][x]--
          }
        }
      }
    }
    n, _ = fmt.Sscanf(line, "toggle %d,%d through %d,%d\n", &x0, &y0, &x1, &y1)
    if n == 4 {
      for y := y0; y <= y1; y++ {
        for x := x0; x <= x1; x++ {
          lights[y][x] += 2
        }
      }
    }
  }

  count := 0
  for y := 0; y != 1000; y++ {
    for x := 0; x != 1000; x++ {
      count += lights[y][x]
    }
  }

  return count
}
