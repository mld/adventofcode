package day4

import (
  "crypto/md5"
  "fmt"
  "strconv"
  "strings"
)

func PartOne(input string) int {
  //key := "ckczppom"
  //data := []byte("These pretzels are making me thirsty.")
  //fmt.Printf("%x\n", md5.Sum(data))
  key := input
  n := 1
  for {
    s := strconv.Itoa(n)
    hash := fmt.Sprintf("%x", md5.Sum( []byte(key+s)))
    if strings.HasPrefix(hash, "00000") {
      return n
    }
    n++
  }
}
