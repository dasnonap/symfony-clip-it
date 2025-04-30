<?php

namespace App\Security\Algorithms;

use LogicException;

class SHA
{
    function __construct(
        private int $bitSize = 256
    ) {}

    /**
     * Hashes the incomming parts 
     */
    function hash(mixed $uniqueValue): string
    {
        $currentTime = time();

        $currentTimeBinary = $this->toBinary($currentTime);
        $uniqueValueBinary = $this->toBinary($uniqueValue);

        $binarySequence = sprintf('%s%s', $currentTimeBinary, $uniqueValueBinary);

        $sequence = $this->padSequence($binarySequence);

        $blocks = $this->createBlocks($sequence);
        dd($blocks);
    }

    /**
     * convert mixed value to binary code
     * @param mixed $value
     * @return string the binary representation
     */
    private function toBinary(mixed $value): string
    {
        if (empty($value)) {
            throw new LogicException("Value provided can't be null.");
        }

        if (is_int($value)) {
            $binary = (string)decbin($value);

            return str_pad($binary, 8, '0', STR_PAD_LEFT);
        }

        // If value is string, continue parsing
        $stringChars = str_split($value);
        $binarySymbols = [];

        foreach ($stringChars as $char) {
            $binaryCode = (string)decbin(ord($char));
            $binarySymbols[] = str_pad($binaryCode, 8, '0', STR_PAD_LEFT);
        }

        if (empty($binarySymbols)) {
            throw new LogicException("String can't be parsed to binary.");
        }

        return implode('', $binarySymbols);
    }

    /**
     * pads the original sequence by needed steps
     * @param string $binarySequence 
     * @return string the 512 length padded sequence
     */
    private function padSequence(string $binarySequence): string
    {
        // Save the original sequence length
        $originalLength = strlen($binarySequence);

        // Completed sequence starts with original binary sequence string
        $completedSequence = $binarySequence;

        // Add 1 at the end of the original sequence
        $completedSequence = sprintf('%s1', $completedSequence);

        // Count zeros padding number
        $zeroCount = 448 - strlen($completedSequence);
        $zeroSequence = str_pad('0', $zeroCount, '0', STR_PAD_BOTH);

        // Message Length Representation
        $lengthBinary = (string)decbin($originalLength);
        $lengthBinary = str_pad($lengthBinary, 64, '0', STR_PAD_LEFT);

        // Return the concatenation, but first check if the size is correct = 512 bits
        $completedSequence = sprintf('%s%s%s', $completedSequence, $zeroSequence, $lengthBinary);

        if (strlen($completedSequence) !== 512) {
            throw new LogicException('Algorithm is not correct length.');
        }

        return $completedSequence;
    }

    private function createBlocks(string $sequence)
    {
        $blocks = array_filter(explode(',', chunk_split($sequence, 32, ',')));


        dd($blocks);
    }

    private function smallSigmaZero(): string
    {
        dd();
    }
}
