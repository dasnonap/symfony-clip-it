<?php

use App\Security\Algorithms\SHA;
use PHPUnit\Framework\TestCase;

class Hashing extends TestCase
{
    function testSHAGeneration()
    {
        $hashingAlgorithm = new SHA();
        $hashingAlgorithm->hash(35);
    }
}
