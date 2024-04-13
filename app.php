#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use LogParser\LogEntry;

function readTheFile($path)
{
    $handle = fopen($path, "r");

    while (!feof($handle)) {
        yield trim(fgets($handle));
    }

    fclose($handle);
}

function parseLogEntries($filePath)
{
    $serialCounts = [];

    foreach (readTheFile($filePath) as $line) {
        $logEntry = new LogEntry($line);

        if (!empty($logEntry->serial)) {
            if (isset($serialCounts[$logEntry->serial])) {
                $serialCounts[$logEntry->serial]++;
            } else {
                $serialCounts[$logEntry->serial] = 1;
            }
        }
    }

    // Sort the serials by occurrence, maintaining key association
    arsort($serialCounts);

    // Get the top 10 most frequent serial numbers
    $topSerials = array_slice($serialCounts, 0, 10, true);

    return $topSerials;
}

$filePath = "updatev12-access-pseudonymized.log";
$topSerials = parseLogEntries($filePath);

echo "Top Serial Numbers:\n";
foreach ($topSerials as $serial => $count) {
    echo "Serial: $serial, Count: $count\n";
}
