package day6

import (
  "fmt"
)

// turn on 0,0 through 999,999 would turn on (or leave on) every light.
// toggle 0,0 through 999,0 would toggle the first line of 1000 lights, turning off the ones that were on, and turning on the ones that were off.
// turn off 499,499 through 500,500 would turn off (or leave off) the middle four lights.


func PartOne(input []string) int {
  var lights [1000][1000]bool

  for _, line := range input {
    var x0, y0, x1, y1, n int

    n, _ = fmt.Sscanf(line, "turn on %d,%d through %d,%d\n", &x0, &y0, &x1, &y1)
    if n == 4 {
      for y := y0; y <= y1; y++ {
        for x := x0; x <= x1; x++ {
          lights[y][x] = true
        }
      }
    }
    n, _ = fmt.Sscanf(line, "turn off %d,%d through %d,%d\n", &x0, &y0, &x1, &y1)
    if  n == 4 {
      for y := y0; y <= y1; y++ {
        for x := x0; x <= x1; x++ {
          lights[y][x] = false
        }
      }
    }
    n, _ = fmt.Sscanf(line, "toggle %d,%d through %d,%d\n", &x0, &y0, &x1, &y1)
    if n == 4 {
      for y := y0; y <= y1; y++ {
        for x := x0; x <= x1; x++ {
          lights[y][x] = !lights[y][x]
        }
      }
    }
  }

  count := 0
  for y := 0; y != 1000; y++ {
    for x := 0; x != 1000; x++ {
      if lights[y][x] {
        count++
      }
    }
  }

  return count
}
