<?php

namespace LogParser;


/** Subset of all the specs field - only those which are needed are given in this class */
class Specs
{
    public string $mac;
    public string $mem;

    public function __construct(array $data)
    {
        $this->mac = $data['mac'] ?? '';
        $this->mem = $data['mem'] ?? '';
    }

    /** Returns a string that identifies the hardware type of this device. */
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
