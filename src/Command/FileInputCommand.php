<?php


namespace App\Command;

use Symfony\Component\Console\Command\Command;


abstract class FileInputCommand extends Command
{
    protected function parseFiles($filenames = null)
    {
        $contents = [];
        if ($filenames !== null) {
            if (!is_array($filenames)) {
                $filenames = [$filenames];
            }
            foreach ($filenames as $filename) {
                $contents = file($filename);
            }
        } else {
            if (0 === ftell(STDIN)) {
                while ($row = fgets(STDIN)) {
                    $contents[] = trim($row);
                }
            } else {
                throw new \RuntimeException("Please provide one or more filenames or pipe content to STDIN.");
            }
        }
        return $contents;
    }

}