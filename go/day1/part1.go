package day1

func PartOne(input []byte) int {
  pos := 0

  for _, c := range input {
    switch string(c) {
      case "(":
        pos++
      case ")":
        pos--
    }
  }
  
  return pos
}
