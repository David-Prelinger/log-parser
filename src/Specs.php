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
        $this->diskRoot = $data['diskRoot'] ?? '';
        $this->diskData = $data['diskData'] ?? '';
        $this->uptime = $data['uptime'] ?? '';
        $this->fwversion = $data['fwversion'] ?? '';
        $this->l2tp = $data['l2tp'] ?? '';
        $this->qos = $data['qos'] ?? '';
        $this->httpaveng = $data['httpaveng'] ?? '';
        $this->spcf = $data['spcf'] ?? '';
    }
}
