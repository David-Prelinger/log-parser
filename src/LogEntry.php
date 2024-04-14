<?php

namespace LogParser;

/** Represents one line in the log. Only has those attributes that are needed for the tasks */
class LogEntry
{
    public string $serial;
    public Specs $specs;

    public function __construct(string $logLine)
    {
        if (preg_match('/serial=([^ ]+)/', $logLine, $matches)) {
            $this->serial = $matches[1];
        }

        if (preg_match('/specs=([^ ]+)/', $logLine, $matches)) {
            $rawSpecs = $matches[1];
            $decodedBase64 = base64_decode($rawSpecs);
            if ($decodedBase64) {
                $gzDecoded = @gzdecode($decodedBase64);
                if ($gzDecoded) {
                    $data = json_decode($gzDecoded, true);
                    if (is_array($data)) {
                        $this->specs = new \LogParser\Specs($data);
                    }
                }
            }
        }
    }
}
