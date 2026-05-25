<?php

namespace App\Services;

class RandomStringGenerator
{   

    // Generate a random string

    public function makeHex(int $length = 11): string
    {
        return bin2hex(random_bytes($length / 2));
    }



    
}