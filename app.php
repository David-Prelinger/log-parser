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
    $hardwareClasses = [];

    foreach (readTheFile($filePath) as $line) {
        $logEntry = new LogEntry($line);

        if (!empty($logEntry->serial)) {
            if (isset($serialCounts[$logEntry->serial]['count'])) {
                $serialCounts[$logEntry->serial]['count']++;
            } else {
                $serialCounts[$logEntry->serial]['count'] = 1;
            }
            if (!empty($logEntry->specs->mac)) {
                $serialCounts[$logEntry->serial]['devices'][$logEntry->specs->mac] = true;
            }

            if (isset($logEntry->specs)) {
                $hardwareClasses[$logEntry->specs->getHardwareType()][$logEntry->serial] = true;
            }
        }
    }

    // Sort the serials by occurrence, maintaining key association
    uasort($serialCounts, function ($a, $b) {
        return $b['count'] - $a['count'];
    });

    // Get the top 10 most frequent serial numbers
    $topSerials = array_slice($serialCounts, 0, 10, true);

    echo "Top Serial Numbers:\n";

    foreach ($topSerials as $serial => $data) {
        echo "Serial: $serial, Count:" . $data['count'] . "\n";
    }

    // Sort by the number of unique devices
    uasort($serialCounts, function ($a, $b) {
        $aDevicesCount = isset($a['devices']) ? count($a['devices']) : 0;
        $bDevicesCount = isset($b['devices']) ? count($b['devices']) : 0;
        return $bDevicesCount - $aDevicesCount;
    });


    // Display top 10 serials by unique devices
    echo "Top 10 Serials by Unique Devices:\n";
    foreach (array_slice($serialCounts, 0, 10, true) as $serial => $data) {
        echo "Serial: $serial, Unique Devices: " . count($data['devices'] ?? []) . "\n";
    }

    // Sort the serials by occurrence, maintaining key association
    uasort($hardwareClasses, function ($a, $b) {
        return count($b) - count($a);
    });

    echo "Hardware classes\n";
    // arsort($hardwareClasses);
    foreach ($hardwareClasses as $hardware => $data) {
        $amountOfSerialLicenseNumbers = count($data);
        echo "Hardware: $hardware, Amount of serial numbers: $amountOfSerialLicenseNumbers \n";
    }
}

$filePath = "updatev12-access-pseudonymized.log";
parseLogEntries($filePath);
