<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;

abstract class FileInputCommand extends Command
{
    /**
     * @param array<string>|string|null $filenames
     * @param string $trimMask
     * @return array<string>
     */
    protected function parseFiles($filenames = null, string $trimMask = " \t\n\r\0\x0B"): array
    {
        $contents = [];
        if ($filenames !== null) {
            if (!is_array($filenames)) {
                $filenames = [$filenames];
            }
            foreach ($filenames as $filename) {
                $tmpfile = file($filename);
                if (is_array($tmpfile)) {
                    $contents = array_merge($contents, $tmpfile);
                }
            }
        } else {
            if (0 === ftell(STDIN)) {
                while ($row = fgets(STDIN)) {
                    $contents[] = trim($row, $trimMask);
                }
            } else {
                throw new \RuntimeException("Please provide one or more filenames or pipe content to STDIN.");
            }
        }
        return $contents;
    }
}
