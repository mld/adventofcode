<?php


namespace App\Day13;

class Computer
{
    private $debug;
    private $debugId;
    private $opcodes;
    private $counter;

    private $code;
    private $opcode;
    private $address;
    private $values;
    private $modes;
    private $addressInput;
    private $addressRelative;
    private $autoInput;
    public $output;
    public $pauseReason;
    public $running;

    public function __construct($codetext = '99', $autostart = true, $debug = true, $debugId = 0)
    {
        $this->debug = $debug; // set to true to see output on screen
        $this->debugId = $debugId;
        $this->opcodes = [
            1 => ['label' => 'add', 'count' => 3],
            2 => ['label' => 'mul', 'count' => 3],
            3 => ['label' => ' in', 'count' => 1],
            4 => ['label' => 'out', 'count' => 1],
            5 => ['label' => 'jit', 'count' => 2], // jump if true
            6 => ['label' => 'jif', 'count' => 2], // jump if false
            7 => ['label' => 'jls', 'count' => 3], // jump if less
            8 => ['label' => 'jeq', 'count' => 3], // jump if equal
            9 => ['label' => 'rel', 'count' => 1],
            99 => ['label' => 'die', 'count' => 0],
        ];
        $this->values = [0, 0, 0];
        $this->address = [0, 0, 0];
        $this->modes = [0, 0, 0]; // 0=address, 1=immediate, 2=relative
        $this->addressInput = -1;
        $this->counter = 0;
        $this->addressRelative = 0;
        $this->running = false;
        $this->pauseReason = ''; // pause on input, output, future (which will show up here)

        if (!is_array($codetext) && trim($codetext) != '') {
            $this->code = explode(',', $codetext);
        } else {
            $this->code = $codetext;
        }

        if (count($this->code) > 0) {
            foreach ($this->code as $index => $value) {
                $this->code[$index] = floatval(trim($value));
            }
            if ($autostart == true) {
                $this->run();
            }
        }
    }

    public function setAutoInput($f)
    {
        if (is_callable($f)) {
            $this->autoInput = $f;
        }
    }

    public function input($value)
    {
        $this->code[$this->addressInput] = $value;
        if ($this->debug == true) {
            echo ' ' . str_pad($this->addressInput, 2, ' ', STR_PAD_LEFT) . ' INPUT ' . $value;
        }
        $this->run();
    }

    public function run()
    {
        if (count($this->code) < 1) {
            return;
        } // safety check, in case codetext variable was empty (file not found)
        $continue = true;
        $this->running = true;
        while ($continue == true) {
            $this->decodeOpcode();
            $log = '';
            if (($this->opcode == 1) || ($this->opcode == 2)) { // add or mul
                $a = $this->values[0];
                $b = $this->values[1];
                if ($this->opcode == 1) {
                    $c = $a + $b;
                }
                if ($this->opcode == 2) {
                    $c = $a * $b;
                }
                $this->code[$this->address[2]] = $c;
                $log = ' c= ' . $c;
            }
            if ($this->opcode == 3) {  // input (memorize address and pause, input value from main program)
                if (is_callable($this->autoInput)) {
                    // If auto_input is a function, use that for input
                    $value = ($this->autoInput)();
                    $this->code[$this->addressInput] = $value;
                    if ($this->debug == true) {
                        echo ' ' . str_pad($this->addressInput, 2, ' ', STR_PAD_LEFT) . ' INPUT ' . $value;
                    }
                } else {
                    $this->pauseReason = 'input';
                    $this->addressInput = $this->address[0];
                    $continue = false;
                }
            }
            if ($this->opcode == 4) {  // output
                $this->output = $this->values[0];
                $log = ' out=' . $this->output;
                $this->pauseReason = 'output';
                $continue = false;
            }
            if ($this->opcode == 5) { // jump if true
                if ($this->values[0] != 0) {
                    $this->counter = $this->values[1];
                    $log = ' JIT=' . $this->counter;
                }
            }
            if ($this->opcode == 6) { // jump if false
                if ($this->values[0] == 0) {
                    $this->counter = $this->values[1];
                    $log = ' JIF=' . $this->counter;
                }
            }
            if ($this->opcode == 7) { // jump if less
                $c = ($this->values[0] < $this->values[1]) ? 1 : 0;
                $this->code[$this->address[2]] = $c;
                $log = ' JLS ' . $c . ' -> ' . $this->address[2];
            }
            if ($this->opcode == 8) { // jump if eq
                $c = ($this->values[0] == $this->values[1]) ? 1 : 0;
                $this->code[$this->address[2]] = $c;
                $log = ' JEQ, ' . $c . ' -> ' . $this->address[2];
            }
            if ($this->opcode == 9) { // adjust relative address
                $this->addressRelative += $this->values[0];
                $log = ' REL = ' . $this->addressRelative;
            }
            if ($this->opcode == 99) {
                $log = " EXIT\n";
                $this->running = false;
                $continue = false;
            }
            if ($this->debug == true) {
                echo $log;
            }
        }
    }

    private function getValue($address)
    {
        return (isset($this->code[$address]) == true) ? $this->code[$address] : 0;
    }

    private function getCounterValue($autoincrement = true)
    {
        $value = (isset($this->code[$this->counter]) == true) ? $this->code[$this->counter] : 0;
        if ($autoincrement == true) {
            $this->counter++;
        }
        return $value;
    }

    private function decodeOpcode()
    {
        $this->opcode = $this->getCounterValue();
        if ($this->opcode < 0) {
            die("Invalid opcode encountered at address " . ($this->counter - 1) . ": $this->opcode");
        }
        $temp = str_pad($this->opcode, 5, '0', STR_PAD_LEFT);
        for ($i = 0; $i < 3; $i++) {
            $this->address[$i] = -1;
            $this->values[$i] = 0;
            $this->modes[$i] = 0;
        }
        $this->opcode = intval(substr($temp, 3, 2));
        $valid = false;
        foreach ($this->opcodes as $key => $value) {
            if ($this->opcode == $key) {
                $valid = true;
            }
        }
        if ($valid == false) {
            die("Encountered invalid opcode at offset $this->counter! [opcode=$this->opcode]\n");
        }

        for ($i = 0; $i < $this->opcodes[$this->opcode]['count']; $i++) {
            $this->modes[$i] = ord(substr($temp, 2 - $i, 1)) - 0x30;
            $value = $this->getCounterValue(); // auto increments counter
            if ($this->modes[$i] == 1) {
                $this->values[$i] = $value;
            }
            if ($this->modes[$i] == 0) {
                $this->address[$i] = $value;
                $this->values[$i] = $this->getValue($this->address[$i]);
            }
            if ($this->modes[$i] == 2) {
                $this->address[$i] = $this->addressRelative + $value;
                $this->values[$i] = $this->getValue($this->address[$i]);
            }
        }
        if ($this->debug == true) {
            echo "\n" . str_pad($this->debugId, 2, ' ', STR_PAD_LEFT) . ' ' .
                str_pad($this->counter, 6, ' ', STR_PAD_LEFT) . ' ' .
                str_pad($this->opcode, 2, ' ', STR_PAD_LEFT) . ' ' .
                str_pad($this->opcodes[$this->opcode]['label'], 6, ' ', STR_PAD_LEFT) . ' ' .
                'm=' . $this->modes[0] . $this->modes[1] . $this->modes[2] . ' ' .
                'a=[ ';
            for ($i = 0; $i < 3; $i++) {
                echo str_pad($this->address[$i], 6, ' ', STR_PAD_LEFT) . ' ';
            }
            echo ' ] ' . 'v=[ ';
            for ($i = 0; $i < 3; $i++) {
                echo str_pad($this->values[$i] > 99999999 ? dechex($this->values[$i]) : $this->values[$i], 8, ' ',
                        STR_PAD_LEFT) . ' ';
            }
            echo ' ]';
        }
    }

    public function reset($autostart = true)
    {
        $this->counter = 0;
        if ($autostart == true) {
            $this->run();
        }
    }
}