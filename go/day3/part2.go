package day3

func PartTwo(input string) int {
  type Point struct {
    x int
    y int
  }

  dat := []byte(input)

  santasmap := make(map[Point]int)
  pos := Point{0,0}
  santapos := Point{0,0}
  robopos := Point{0,0}
  santasmap[pos] = 1

  for n, c := range dat {
    if n % 2 == 0 {
      pos = santapos
    } else {
      pos = robopos
    }

    switch string(c) {
      case "^":
        pos.y++
        santasmap[pos]++
      case ">":
        pos.x++
        santasmap[pos]++
      case "v":
        pos.y--
        santasmap[pos]++
      case "<":
        pos.x--
        santasmap[pos]++
    }
    if n % 2 == 0 {
      santapos = pos
    } else {
      robopos = pos
    }
  }

  return len(santasmap)
}
