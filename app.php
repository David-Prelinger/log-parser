#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use LogParser\LogEntry;

/** Reads the file line by line */
function readTheFile($path)
{
    shell_exec(escapeshellcmd("unxz $path"));
    $handle = fopen(substr($path, 0, -3), "r");

    while (!feof($handle)) {
        yield trim(fgets($handle));
    }

    fclose($handle);
}

/** Iterates through each line in the given file and displays the results for tasks 1-3. */
function parseLogEntries($filePath)
{
    $serialCounts = [];
    $hardwareClasses = [];

    foreach (readTheFile($filePath) as $line) {
        $logEntry = new LogEntry($line);

        if (!empty($logEntry->serial)) {
            // Task 1: Count how often each serial number was found in log
            if (isset($serialCounts[$logEntry->serial]['count'])) {
                $serialCounts[$logEntry->serial]['count']++;
            } else {
                $serialCounts[$logEntry->serial]['count'] = 1;
            }

            // Task 2: Count how many devices use each serial number
            if (!empty($logEntry->specs->mac)) {
                $serialCounts[$logEntry->serial]['devices'][$logEntry->specs->mac] = true;
            }

            // Task 3: Count individual serial numbers by hardware type
            if (isset($logEntry->specs)) {
                $hardwareClasses[$logEntry->specs->getHardwareType()][$logEntry->serial] = true;
            }
        }
    }

    printTopSerialNumbers($serialCounts);

    printSerialsInstalledOnMostDevices($serialCounts);

    printHardwareClassesWithAmountOfSerialNumbers($hardwareClasses);
}

/** Prints the top 10 license serial numbers which requested the server the most to the console. */
function printTopSerialNumbers($serialCounts)
{
    uasort($serialCounts, function ($a, $b) {
        return $b['count'] - $a['count'];
    });

    $topSerials = array_slice($serialCounts, 0, 10, true);

    echo "\n\nTop 10 license serial numbers which requested the server the most:\n";

    foreach ($topSerials as $serial => $data) {
        echo "Serial: $serial, Count:" . $data['count'] . "\n";
    }
}

/** Prints the top 10 license serial numbers which are installed on most unique devices. */
function printSerialsInstalledOnMostDevices($serialCounts)
{
    // Sort by the number of unique devices
    uasort($serialCounts, function ($a, $b) {
        $aDevicesCount = isset($a['devices']) ? count($a['devices']) : 0;
        $bDevicesCount = isset($b['devices']) ? count($b['devices']) : 0;
        return $bDevicesCount - $aDevicesCount;
    });

    echo "\n\nTop 10 Serials by Unique Devices:\n";
    foreach (array_slice($serialCounts, 0, 10, true) as $serial => $data) {
        echo "Serial: $serial, Unique Devices: " . count($data['devices'] ?? []) . "\n";
    }
}

/** Prints the different hardware classes with the amount of serial numbers assigned to them. */
function printHardwareClassesWithAmountOfSerialNumbers($hardwareClasses)
{
    uasort($hardwareClasses, function ($a, $b) {
        return count($b) - count($a);
    });

    echo "\n\nHardware classes\n";
    foreach ($hardwareClasses as $hardware => $data) {
        $amountOfSerialLicenseNumbers = count($data);
        echo "Hardware: $hardware, Amount of serial numbers: $amountOfSerialLicenseNumbers \n";
    }
}

if ($argc > 1) {
    parseLogEntries($argv[1]);
} else {
    echo "Please provide a filename as an argument.\n";
}
