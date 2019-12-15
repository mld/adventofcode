<?php


namespace App\Day14;


class NanoFactory
{
    protected $components;
    protected $debug;

    public function __construct($raw = [], $debug = false)
    {
        $this->debug = $debug;
        $this->parse($raw);
    }

    protected function parse($raw = [])
    {
        foreach ($raw as $line) {
            [$left, $right] = explode('=>', trim($line), 2);
            if (strpos($left, ',') !== false) {
                $parts = explode(',', $left);
            } else {
                $parts = [$left];
            }

            [$amount, $produced] = explode(' ', trim($right));
            $this->components[$produced]['output'] = $amount;

            foreach ($parts as $part) {
                [$amount, $chemical] = explode(' ', trim($part));
                $this->components[$produced]['input'][$chemical] = $amount;
            }
        }
    }

    public function oresRequired($chemical, $amount, &$waste = [])
    {
        if ($chemical == 'ORE') {
            return $amount;
        }

        if (!isset($waste[$chemical])) {
            $waste[$chemical] = 0;
        }

        // Reuse "wasted" chemicals
        $reuse = min($amount, $waste[$chemical]);
        $amount -= $reuse;
        $waste[$chemical] -= $reuse;

        $produced = $this->components[$chemical]['output'];
        $inputs = $this->components[$chemical]['input'];

        // How many "batches" do we need?
        $batches = ceil($amount / $produced);

        $ores = 0;

        foreach ($inputs as $input => $required) {
            $ores += $this->oresRequired($input, $batches * $required, $waste);
        }

        $waste[$chemical] += $batches * $this->components[$chemical]['output'] - $amount;

        return $ores;
    }
}