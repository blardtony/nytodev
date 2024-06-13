<?php

namespace App\Service;

class TokenService
{
    public function generateToken(int $length = 32): string
    {
        if ($length < 1) {
            $length = 32;
        }
        return bin2hex(random_bytes($length));
    }
}
