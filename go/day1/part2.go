package day1

func PartTwo(input []byte) int {
  pos := 0
  basement := 0
  found := false

  for _, c := range input {
    basement++
    switch string(c) {
      case "(":
        pos++
      case ")":
        pos--
    }

    if found == false && pos == -1 {
      return basement
    }
  }
  return -1
}
