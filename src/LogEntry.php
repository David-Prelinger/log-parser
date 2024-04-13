<?php

namespace LogParser;


class LogEntry
{
    public string $clientIpAddress;
    public string $identdInformation;
    public string $timestamp;
    public string $requestMethod;
    public string $requestUri;
    public string $httpVersion;
    public int $statusCode;
    public int $contentLength;
    public string $proxy;
    public float $requestTime;
    public string $serial;
    public string $version;
    public ?Specs $specs; // Encoded string
    public string $notAfter;
    public int $remainingDays;

    public function __construct(string $logLine)
    {
        if (preg_match('/serial=([^ ]+)/', $logLine, $matches)) {
            $this->serial = $matches[1];
        } else {
            echo "Serial number field not found.\n";
        }

        if (preg_match('/specs=([^ ]+)/', $logLine, $matches)) {
            $rawSpecs = $matches[1];
            $decodedBase64 = base64_decode($rawSpecs);
            if (!$decodedBase64) {
                echo "Error decoding base64\n";
            } else {
                $gzDecoded = @gzdecode($decodedBase64);
                if (!$gzDecoded) {
                    echo "Error decoding gz\n";
                } else {
                    $data = json_decode($gzDecoded, true);
                    if (is_array($data)) {
                        $this->specs = new \LogParser\Specs($data);
                    } else {
                        echo "Error decoding JSON\n";
                    }
                }
            }
        } else {
            echo "Specs field not found.\n";
        }
    }
}
