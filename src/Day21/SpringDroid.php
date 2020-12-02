<?php


namespace App\Day21;


use App\Day13\Computer;
use App\Day17\Ascii;

class SpringDroid extends Ascii
{
    public function hullDamage($mode = 'WALK')
    {
        $code = $this->code;
//        $code[0] = 2; // wake up the robot

        // AND X Y sets Y to true if both X and Y are true; otherwise, it sets Y to false.
        // OR X Y sets Y to true if at least one of X or Y is true; otherwise, it sets Y to false.
        // NOT X Y sets Y to true if X is false; otherwise, it sets Y to false.

        // J = (!A | !B | !C) & D
        // J = !(A & B & C) & D
        $commands['WALK'] = [
            0 => str_split('NOT A J' . chr(10)),
            1 => str_split('NOT J J' . chr(10)),
            2 => str_split('AND B J' . chr(10)),
            3 => str_split('AND C J' . chr(10)),
            4 => str_split('NOT J J' . chr(10)),
            5 => str_split('AND D J' . chr(10)),
            6 => str_split('WALK' . chr(10)), // Execute
        ];

        // J = (!A & D) | (!B & D) | (!C & D & H)
        // J = (!A | !B | (!C & H)) & D
        $commands['RUN'] = [
            0 => str_split('NOT C J' . chr(10)),
            1 => str_split('AND H J' . chr(10)),
            2 => str_split('NOT B T' . chr(10)),
            3 => str_split('OR T J' . chr(10)),
            4 => str_split('NOT A T' . chr(10)),
            5 => str_split('OR T J' . chr(10)),
            6 => str_split('AND D J' . chr(10)),
            7 => str_split('RUN' . chr(10)), // Execute
        ];

        $command = 0;
        $pos = 0;

        $computer = new Computer($code, true, false);
        $last = null;
        do {
            if ($computer->pauseReason == 'output') {
                if (chr($computer->output) == '.') {
                    printf(" ");
                } else {
                    printf("%c", $computer->output);
                }
                $computer->run();

            }
            if ($computer->pauseReason == 'input') {
                $computer->input(ord($commands[$mode][$command][$pos]));
                if (ord($commands[$mode][$command][$pos]) == 10) {
                    $command++;
                    $pos = 0;
                } else {
                    $pos++;
                }
            }
        } while ($computer->running || $command <= 3);

        return $computer->output;

    }
}