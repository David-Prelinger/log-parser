<?php

namespace LogParser;


class Specs
{
    public string $mac;
    public string $architecture;
    public string $machine;
    public string $mem;
    public string $cpu;
    public string $diskRoot;
    public string $diskData;
    public string $uptime;
    public string $fwversion;
    public string $l2tp;
    public string $qos;
    public string $httpaveng;
    public string $spcf;


    public function __construct(array $data)
    {
        $this->mac = $data['mac'] ?? '';
        $this->architecture = $data['architecture'] ?? '';
        $this->machine = $data['machine'] ?? '';
        $this->mem = $data['mem'] ?? '';
        $this->cpu = $data['cpu'] ?? '';
        $this->diskRoot = $data['disk_root'] ?? '';
        $this->diskData = $data['disk_data'] ?? '';
        $this->uptime = $data['uptime'] ?? '';
        $this->fwversion = $data['fwversion'] ?? '';
        $this->l2tp = $data['l2tp'] ?? '';
        $this->qos = $data['qos'] ?? '';
        $this->httpaveng = $data['httpaveng'] ?? '';
        $this->spcf = $data['spcf'] ?? '';
    }

    public function getHardwareType(): string
    {
        $numberOfKilobytes = (int)filter_var($this->mem, FILTER_SANITIZE_NUMBER_INT);
        $numberOfGigabytes = round($numberOfKilobytes / 1024 / 1024);
        // Many memory amounts come close to typical memory numbers like 64, 32 etc.
        // Therefore I assume that those typical numbers are the real groups, not odd numbers
        // like 31GB.
        if ($numberOfGigabytes > 60 && $numberOfGigabytes < 68) {
            $numberOfGigabytes = 64;
        } else if ($numberOfGigabytes > 28 && $numberOfGigabytes < 36) {
            $numberOfGigabytes = 32;
        } else if ($numberOfGigabytes > 14 && $numberOfGigabytes < 18) {
            $numberOfGigabytes = 16;
        } else if ($numberOfGigabytes > 6 && $numberOfGigabytes < 10) {
            $numberOfGigabytes = 8;
        } else if ($numberOfGigabytes < 6 && $numberOfGigabytes > 3) {
            $numberOfGigabytes = 4;
        }

        return $numberOfGigabytes . "GB";
    }
}
