package day3

func PartOne(input string) int {
  type Point struct {
    x int
    y int
  }

  dat := []byte(input)

  santasmap := make(map[Point]int)
  pos := Point{0,0}
  santapos := Point{0,0}
  santasmap[pos] = 1

  for _, c := range dat {
    pos = santapos

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
    santapos = pos
  }

  return len(santasmap)
}
