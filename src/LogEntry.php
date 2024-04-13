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
    }
}