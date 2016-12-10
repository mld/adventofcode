package day4

import (
  "crypto/md5"
  "fmt"
  "strconv"
  "strings"
)

func PartTwo(input string) int {
  key := input
  n := 1
  for {
    s := strconv.Itoa(n)
    hash := fmt.Sprintf("%x", md5.Sum( []byte(key+s)))
    if strings.HasPrefix(hash, "000000") {
      return n
    }
    n++
  }
}
