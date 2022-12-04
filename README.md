# Advent of Code

So. This is a weird one. I figured I needed to get better at writing Laravel Artisan commands, so here we are. A huge bunch of bloat that will never be needed for a cli, ever. And not much code related to Laravel or Artisan commands in the actual day to day of solving AoC puzzles.

Anyway, go check out [Advent of Code](https://adventofcode.com/) if you're not familiar already. And if you think it's fun and have the means, please consider donating to the creator who spends a whole lot of his spare time every year to create this for us!

## Tooling

First off, make sure you have your AoC session token in the ENV `AOC_SESSION_COOKIE` and have run `composer install`. Then you can use the few commands that are available.

### `aoc:make <yyyy> <dd>`

This commands downloads the puzzle and input for the day given in params, and also converts the puzzle to markdown and extracts (previously give) answers from the puzzle description.

```shell
$ artisan aoc:make 2022 1
Calling make:solution
   INFO  AbstractSolution [app/Solutions/_2022/_01.php] created successfully.

Fetching input
Fetching puzzle
```

Puzzle and input files are stored in `storage/app/<year>/<day>/` or `app/storage/2022/01/` in the example above.

The PHP file (`app/Solutions/_2022/_01.php` in the example above) is created from the stub file in `stubs/solution.stub`.

There's also an option `-f|--fetch` to re-fetch the puzzle from the AoC site. This is needed to fetch part 2 after you've completed part 1, and to extract the answers connected to your `input`.

### `aoc <yyyy> <dd> <part> [<input>]`

This command tries to run your code against the given input file, and if one exists, compares it to the correct answer (for `input`, the answer to part 1 is saved in `input.part1.answer` and for part 2 `input.part2.answer`).

```shell
$ ls storage/app/2022/01/
input			input.part2.answer	puzzle.md		test1.part1.answer
input.part1.answer	puzzle.html		test1			test1.part2.answer

$ artisan aoc 2022 1 1 test1
Solution for 2022/01/1 found in 0.000 seconds:
	24000
 =	24000

$ artisan aoc 2022 1 2 test1
Solution for 2022/01/2 found in 0.000 seconds:
	45000
 =	45000

$ artisan aoc 2022 1 1
Solution for 2022/01/1 found in 0.000 seconds:
	69281
 =	69281

$ artisan aoc 2022 1 2
Solution for 2022/01/2 found in 0.000 seconds:
	201524
 =	201524
```

In this example you can see we're running part 1 and 2 of the first day of the 2022 calendar. We've created a test case from the puzzle data (`test1`, `test1.part1.answer` and `test1.part2.answer`) and can use them to make sure (depending on sneakiness of the puzzle of course :D ) that we're on the right track for a correct answer.

We also have the answers for `input`, since we've re-run the `aoc:make` command with `-f` before writing this readme.
